<?php

require_once __DIR__ . '/vendor/autoload.php';

use League\Csv\Reader;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/.env');

/* connexion */
$server = $_ENV["DATABASE_HOST"];
$user = $_ENV["DATABASE_USER"];
$password = $_ENV["DATABASE_PASSWORD"];
$dataBaseName = $_ENV["DATABASE_NAME"];

try {
    $connexion = new PDO("mysql:host=$server;dbname=$dataBaseName", $user, $password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // récup les colonnes de la table désormais créée par doctrine
    $stmt = $connexion->query("DESCRIBE alim");
    $dbColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    // exclure id (auto increment)
    $dbColumns = array_diff($dbColumns, ["id"]);

    /* csv reader config */
    $csv = Reader::from('./CALNUT2020_2020_07_07.csv'); //createFromPath deprécié
    $csv->setHeaderOffset(0)->setDelimiter(';')->setEscape('"')->setEnclosure('"');

    /* get headers and records */
    $header = array_map('strtolower', $csv->getHeader()); // aligner les headers du CSV en maj sur la casse en bdd... résolution erreur 1364 not nullable column doesn't have a default value

    $records = $csv->getRecords(); // return un itérable de lignes CSV

    // retenir les colonnes du csv présentes en BDD
    $validColumns = array_intersect($dbColumns, $header);

    if (empty($validColumns)) {
        throw new Exception("Aucune colonne correspondante trouvée entre le CSV et la table 'alim'");
    }

    // préparer la requête sql
    $columnsList = implode(', ', array_map(fn($column) => "`$column`", $validColumns));  // backticks autour des noms de colonnes
    $paramsList = implode(', ', array_fill(0, count($validColumns), '?')); // placeholders pour les données à insérer
    $updateList = implode(', ', array_map(fn($column) => "`$column` = VALUES(`$column`)", $validColumns));

    $sql = "INSERT INTO alim ({$columnsList}) VALUES ({$paramsList}) ON DUPLICATE KEY UPDATE {$updateList}";
    $injection = $connexion->prepare($sql);

    /*insertion des données*/
    $connexion->beginTransaction();
    $count = 0;

    /* récup, traiter les données */
    foreach ($records as $record) {
        // aligner les clés du CSV sur la casse de la BDD
        $record = array_change_key_case($record, CASE_LOWER);
        $dataToInsert = [];

       foreach ($validColumns as $column) {
           $value = $record[$column] ?? null;
           $dataToInsert[] = ($value === '' || $value === '-') ? null : $value;
       }

       $injection->execute($dataToInsert);

       $count++;

       // batch processing / commit tous les 500 éléments
       if ($count %500 === 0) {
           $connexion->commit();
           $connexion->beginTransaction();
       }
    }

    $connexion->commit();
    echo "import terminé : $count aliments traités" . "\n";
} catch (PDOException|\League\Csv\Exception $e) {
    echo 'PDO exception ! ' . $e->getMessage() . "\n";
} catch (Throwable $e) {
    echo ' = Error !' . $e->getMessage() . "\n";
}
