<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260220222900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Adds verification_token and token_expires_at columns to user table.
        // Unrelated alim/recipe_alim/subcategory diffs were stripped — those belong to a separate migration.
        $this->addSql('ALTER TABLE user ADD verification_token VARCHAR(64) DEFAULT NULL, ADD token_expires_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649C1CC006B ON user (verification_token)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_8D93D649C1CC006B ON user');
        $this->addSql('ALTER TABLE user DROP verification_token, DROP token_expires_at');
    }
}
