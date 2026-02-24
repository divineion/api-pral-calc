<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

// Charge .env, .env.local, .env.test, .env.test.local dans l'ordre,
// le dernier fichier ayant la priorité sur les précédents.
// APP_ENV est défini à "test" par phpunit.xml.dist, donc Symfony charge .env.test.
if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}
