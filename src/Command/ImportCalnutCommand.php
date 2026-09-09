<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

use Symfony\Component\Console\Input\InputInterface;

use League\Csv\Reader;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Helper\ProgressBar;
use Throwable;

#[AsCommand(name: 'app:import-calnut', description: 'Import Calnut data')]
class ImportCalnutCommand extends Command
{
    public function __construct(
        private readonly Connection $connection,
    )
    {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            // récup les colonnes de la table désormais créée par doctrine
            $stmt = $this->connection->executeQuery("DESCRIBE alim");

            $dbColumns = $stmt->fetchFirstColumn();
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
                throw new RuntimeException("Aucune colonne correspondante trouvée entre le CSV et la table 'alim'");
            }

            // préparer la requête sql
            $columnsList = implode(', ', array_map(fn($column) => "`$column`", $validColumns));  // backticks autour des noms de colonnes
            $paramsList = implode(', ', array_fill(0, count($validColumns), '?')); // placeholders pour les données à insérer
            $updateList = implode(', ', array_map(fn($column) => "`$column` = VALUES(`$column`)", $validColumns));

            $sql = "INSERT INTO alim ({$columnsList}) VALUES ({$paramsList}) ON DUPLICATE KEY UPDATE {$updateList}";
            $injection = $this->connection->prepare($sql);

            /*insertion des données*/
            $this->connection->beginTransaction();
            $count = 0;

            $progressBar = new ProgressBar($output, count($csv));
            $progressBar->start();

            /* récup, traiter les données */
            foreach ($records as $record) {
                // aligner les clés du CSV sur la casse de la BDD
                $record = array_change_key_case($record, CASE_LOWER);
                $dataToInsert = [];

                foreach ($validColumns as $column) {
                    $value = $record[$column] ?? null;
                    $dataToInsert[] = ($value === '' || $value === '-') ? null : $value;
                }

                $injection->executeQuery($dataToInsert);

                $progressBar->advance();

                $count++;

                // batch processing / commit tous les 500 éléments
                if ($count %500 === 0) {
                    $this->connection->commit();
                    $this->connection->beginTransaction();
                }
            }

            $this->connection->commit();

            $progressBar->finish();
            $output->writeln(" Import terminé : $count aliments traités");

            return Command::SUCCESS;
        } catch (Throwable $exception) {
            if ($this->connection->isTransactionActive()) {
                try {
                    $this->connection->rollback();
                } catch (Throwable $rollbackException) {

                }
            }
            $output->writeln(' = Error !' . $exception->getMessage());

            return Command::FAILURE;
        }
    }
}