<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241024144820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date VARCHAR(25) NOT NULL, content JSON NOT NULL, title VARCHAR(255) NOT NULL, total_pral_index VARCHAR(255) DEFAULT NULL, INDEX IDX_3BAE0AA7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, category INT DEFAULT NULL, subcategory INT DEFAULT NULL, title VARCHAR(255) NOT NULL, instructions LONGTEXT NOT NULL, quantities JSON NOT NULL, image LONGBLOB DEFAULT NULL, pral_index NUMERIC(6, 2) DEFAULT NULL, INDEX IDX_DA88B137A76ED395 (user_id), INDEX IDX_DA88B13764C19C1 (category), INDEX IDX_DA88B137DDCA448 (subcategory), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_alim (recipe_id INT NOT NULL, alim_id INT NOT NULL, INDEX IDX_1B34EAEA59D8A214 (recipe_id), INDEX IDX_1B34EAEABF571CE (alim_id), PRIMARY KEY(recipe_id, alim_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subcategory (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_DDCA44812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(180) DEFAULT NULL, avatar VARCHAR(2048) DEFAULT NULL, roles JSON NOT NULL, nutrients_display_preference JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, is_archived TINYINT(1) NOT NULL, member_since VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B13764C19C1 FOREIGN KEY (category) REFERENCES category (id)');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137DDCA448 FOREIGN KEY (subcategory) REFERENCES subcategory (id)');
        $this->addSql('ALTER TABLE recipe_alim ADD CONSTRAINT FK_1B34EAEA59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_alim ADD CONSTRAINT FK_1B34EAEABF571CE FOREIGN KEY (alim_id) REFERENCES alim (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subcategory ADD CONSTRAINT FK_DDCA44812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7A76ED395');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137A76ED395');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B13764C19C1');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137DDCA448');
        $this->addSql('ALTER TABLE recipe_alim DROP FOREIGN KEY FK_1B34EAEA59D8A214');
        $this->addSql('ALTER TABLE recipe_alim DROP FOREIGN KEY FK_1B34EAEABF571CE');
        $this->addSql('ALTER TABLE subcategory DROP FOREIGN KEY FK_DDCA44812469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_alim');
        $this->addSql('DROP TABLE subcategory');
        $this->addSql('DROP TABLE user');
    }
}
