<?php

require_once __DIR__ . '/vendor/autoload.php';

use League\Csv\Reader;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env.prod.local');

/* connexion */
$server = $_ENV["DATABASE_HOST"];
$user = $_ENV["DATABASE_USER"];
$password = $_ENV["DATABASE_TEMPORARY_PASSWORD"];
$dataBaseName = $_ENV["DATABASE_NAME"];

try {
    $connexion = new PDO("mysql:host=$server;dbname=$dataBaseName", $user, $password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $csv = Reader::createFromPath('./CALNUT2020_2020_07_07.csv');

    /* csv reader config */
    $csv->setHeaderOffset(0)->setDelimiter(';')->setEscape('"')->setEnclosure('"');

    /* get headers and records */
    $header = $csv->getHeader();
    $records = $csv->getRecords(); // return un itérable de lignes CSV
    $headerSlug = [];

    /* stocker colonnes */
    $sqlColonne = [];
    $sqlColonneRaw = [];

    foreach ($header as $col) {
        $titre_colonne = $col;
        //
        if ($col == 'FOOD_LABEL') {
            $sqlColonne[] = "`" . $titre_colonne . "` VARCHAR(255)";
        } else {
            $sqlColonne[] = "`" . $titre_colonne . "` text";
        }
        $sqlColonneRaw[] = $titre_colonne;
        $headerSlug[] = $titre_colonne;
    }

    $dropTableQuery = "DROP TABLE IF EXISTS alim";
    $connexion->exec($dropTableQuery);

    $createTableQuery = "CREATE TABLE IF NOT EXISTS alim (
    " . implode(', ', $sqlColonne) . ", 
    UNIQUE(`FOOD_LABEL`)
    );";
    $connexion->exec($createTableQuery);

    /* ajouter colonne id et redéfinir les types de colonnes */
    $changeColTypeQuery = "
        ALTER TABLE `alim`
        ADD `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST,
        MODIFY `alim_code` INT NULL DEFAULT NULL, 
        MODIFY `nrj_kj` FLOAT NULL DEFAULT NULL,
        MODIFY `nrj_kcal` FLOAT NULL DEFAULT NULL,
        MODIFY `eau_g` FLOAT NULL DEFAULT NULL,
        MODIFY `sel_g` FLOAT NULL DEFAULT NULL,
        MODIFY `sodium_mg` FLOAT NULL DEFAULT NULL,
        MODIFY `magnesium_mg` FLOAT NULL DEFAULT NULL,
        MODIFY `phosphore_mg` FLOAT NULL DEFAULT NULL,
        MODIFY `potassium_mg` FLOAT NULL DEFAULT NULL,
        MODIFY `calcium_mg` FLOAT NULL DEFAULT NULL,
        MODIFY `manganese_mg` FLOAT NULL DEFAULT NULL,
        MODIFY `fer_mg` FLOAT NULL DEFAULT NULL,
        MODIFY `cuivre_mg` FLOAT NULL DEFAULT NULL,
        MODIFY `zinc_mg` FLOAT NULL DEFAULT NULL,
        MODIFY `selenium_mcg` FLOAT NULL DEFAULT NULL,
        MODIFY `iode_mcg` FLOAT NULL DEFAULT NULL,
        MODIFY `proteines_g` FLOAT NULL DEFAULT NULL,
        MODIFY `glucides_g` FLOAT NULL DEFAULT NULL,
        MODIFY `sucres_g` FLOAT NULL DEFAULT NULL,
        MODIFY `fructose_g` FLOAT NULL DEFAULT NULL,
        MODIFY `galactose_g` FLOAT NULL DEFAULT NULL,
        MODIFY `lactose_g` FLOAT NULL DEFAULT NULL,
        MODIFY `glucose_g` FLOAT NULL DEFAULT NULL,
        MODIFY `maltose_g` FLOAT NULL DEFAULT NULL,
        MODIFY `saccharose_g` FLOAT NULL DEFAULT NULL,
        MODIFY `amidon_g` FLOAT NULL DEFAULT NULL,
        MODIFY `polyols_g` FLOAT NULL DEFAULT NULL,
        MODIFY `fibres_g` FLOAT NULL DEFAULT NULL,
        MODIFY `lipides_g` FLOAT NULL DEFAULT NULL,
        MODIFY `ags_g` FLOAT NULL DEFAULT NULL,
        MODIFY `agmi_g` FLOAT NULL DEFAULT NULL,
        MODIFY `agpi_g` FLOAT NULL DEFAULT NULL,
        MODIFY `ag_04_0_g` FLOAT NULL DEFAULT NULL,
        MODIFY `ag_06_0_g` FLOAT NULL DEFAULT NULL,
        MODIFY `ag_08_0_g` FLOAT NULL DEFAULT NULL,
        MODIFY `ag_10_0_g` FLOAT NULL DEFAULT NULL,
        MODIFY `ag_12_0_g` FLOAT NULL DEFAULT NULL,
        MODIFY `ag_14_0_g` FLOAT NULL DEFAULT NULL,
        MODIFY `ag_16_0_g` FLOAT NULL DEFAULT NULL,
        MODIFY `ag_18_0_g` FLOAT NULL DEFAULT NULL, 
        MODIFY `ag_18_1_ole_g` FLOAT NULL DEFAULT NULL,
        MODIFY `ag_18_2_lino_g` FLOAT NULL DEFAULT NULL,
        MODIFY `ag_18_3_a_lino_g` FLOAT NULL DEFAULT NULL,
        MODIFY `ag_20_4_ara_g` FLOAT NULL DEFAULT NULL,
        MODIFY `ag_20_5_epa_g` FLOAT NULL DEFAULT NULL,
        MODIFY `ag_20_6_dha_g` FLOAT NULL DEFAULT NULL,
        MODIFY `retinol_mcg` FLOAT NULL DEFAULT NULL, 
        MODIFY `beta_carotene_mcg` FLOAT NULL DEFAULT NULL,
        MODIFY `vitamine_d_mcg` FLOAT NULL DEFAULT NULL,
        MODIFY `vitamine_e_mg` FLOAT NULL DEFAULT NULL,
        MODIFY `vitamine_k1_mcg` FLOAT NULL DEFAULT NULL,
        MODIFY `vitamine_k2_mcg` FLOAT NULL DEFAULT NULL,
        MODIFY `vitamine_c_mg` FLOAT NULL DEFAULT NULL,
        MODIFY `vitamine_b1_mg` FLOAT NULL DEFAULT NULL,
        MODIFY `vitamine_b2_mg` FLOAT NULL DEFAULT NULL,
        MODIFY `vitamine_b3_mg` FLOAT NULL DEFAULT NULL,
        MODIFY `vitamine_b5_mg` FLOAT NULL DEFAULT NULL,
        MODIFY `vitamine_b6_mg` FLOAT NULL DEFAULT NULL,
        MODIFY `vitamine_b12_mcg` FLOAT NULL DEFAULT NULL,
        MODIFY `vitamine_b9_mcg` FLOAT NULL DEFAULT NULL,
        MODIFY `alcool_g` FLOAT NULL DEFAULT NULL,
        MODIFY `acides_organiques_g` FLOAT NULL DEFAULT NULL,
        MODIFY `cholesterol_mg` FLOAT NULL DEFAULT NULL,
        MODIFY `alim_grp_code` INT NULL DEFAULT NULL,
        MODIFY `alim_ssgrp_code` INT NULL DEFAULT NULL,
        MODIFY `alim_ssssgrp_code` INT NULL DEFAULT NULL;
    ";

    $connexion->exec($changeColTypeQuery);

    /* préparer la requête d'insertion */
    $sql = "INSERT INTO alim (" . implode(', ', $sqlColonneRaw) . ") VALUES (" . implode(', ', array_fill(0, count($sqlColonneRaw), '?')) . ") 
            ON DUPLICATE KEY UPDATE " . implode(', ', array_map(function($col) { return "$col = VALUES($col)"; }, $sqlColonneRaw));
    $injection = $connexion->prepare($sql);

    /* récup, traiter les données */
    foreach ($records as $record) {
        /* Créer une copie du record */
        $recordCopy = $record;

        $injection->execute(array_values($recordCopy));
    }

    echo "Données insérées.";

} catch (PDOException|\League\Csv\Exception $e) {
    echo $e->getMessage() . ' = PDO exception !';
}
