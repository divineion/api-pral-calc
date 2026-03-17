<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260317181610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alim (id INT AUTO_INCREMENT NOT NULL, food_label VARCHAR(255) NOT NULL, pral_index NUMERIC(5, 2) NOT NULL, alim_code INT NOT NULL, nrj_kj NUMERIC(6, 2) NOT NULL, nrj_kcal NUMERIC(5, 2) NOT NULL, eau_g NUMERIC(5, 2) NOT NULL, sel_g NUMERIC(4, 2) NOT NULL, sodium_mg NUMERIC(7, 2) NOT NULL, magnesium_mg NUMERIC(5, 2) NOT NULL, phosphore_mg NUMERIC(6, 2) NOT NULL, potassium_mg NUMERIC(6, 2) NOT NULL, calcium_mg NUMERIC(6, 2) NOT NULL, manganese_mg NUMERIC(4, 2) NOT NULL, fer_mg NUMERIC(5, 2) NOT NULL, cuivre_mg NUMERIC(4, 2) NOT NULL, zinc_mg NUMERIC(4, 2) NOT NULL, selenium_mcg NUMERIC(5, 2) NOT NULL, iode_mcg NUMERIC(7, 2) NOT NULL, proteines_g NUMERIC(4, 2) NOT NULL, glucides_g NUMERIC(4, 2) NOT NULL, sucres_g NUMERIC(4, 2) NOT NULL, fructose_g NUMERIC(4, 2) NOT NULL, galactose_g NUMERIC(4, 2) NOT NULL, lactose_g NUMERIC(4, 2) NOT NULL, glucose_g NUMERIC(4, 2) NOT NULL, maltose_g NUMERIC(4, 2) NOT NULL, saccharose_g NUMERIC(4, 2) NOT NULL, amidon_g NUMERIC(4, 2) NOT NULL, polyols_g NUMERIC(4, 2) NOT NULL, fibres_g NUMERIC(4, 2) NOT NULL, lipides_g NUMERIC(5, 2) NOT NULL, ags_g NUMERIC(4, 2) NOT NULL, agmi_g NUMERIC(4, 2) NOT NULL, agpi_g NUMERIC(4, 2) NOT NULL, ag_04_0_g NUMERIC(3, 2) NOT NULL, ag_06_0_g NUMERIC(3, 2) NOT NULL, ag_08_0_g NUMERIC(3, 2) NOT NULL, ag_10_0_g NUMERIC(3, 2) NOT NULL, ag_12_0_g NUMERIC(4, 2) NOT NULL, ag_14_0_g NUMERIC(4, 2) NOT NULL, ag_16_0_g NUMERIC(4, 2) NOT NULL, ag_18_0_g NUMERIC(4, 2) NOT NULL, ag_18_1_ole_g NUMERIC(4, 2) NOT NULL, ag_18_2_lino_g NUMERIC(4, 2) NOT NULL, ag_18_3_a_lino_g NUMERIC(4, 2) NOT NULL, ag_20_4_ara_g NUMERIC(3, 2) NOT NULL, ag_20_5_epa_g NUMERIC(4, 2) NOT NULL, ag_20_6_dha_g NUMERIC(4, 2) NOT NULL, retinol_mcg NUMERIC(7, 2) NOT NULL, beta_carotene_mcg NUMERIC(7, 2) NOT NULL, vitamine_d_mcg NUMERIC(7, 2) NOT NULL, vitamine_e_mg NUMERIC(7, 2) NOT NULL, vitamine_k1_mcg NUMERIC(7, 2) NOT NULL, vitamine_k2_mcg NUMERIC(7, 2) NOT NULL, vitamine_c_mg NUMERIC(7, 2) NOT NULL, vitamine_b1_mg NUMERIC(7, 2) NOT NULL, vitamine_b2_mg NUMERIC(7, 2) NOT NULL, vitamine_b3_mg NUMERIC(7, 2) NOT NULL, vitamine_b5_mg NUMERIC(7, 2) NOT NULL, vitamine_b6_mg NUMERIC(7, 2) NOT NULL, vitamine_b12_mcg NUMERIC(7, 2) NOT NULL, vitamine_b9_mcg NUMERIC(7, 2) NOT NULL, alcool_g NUMERIC(4, 2) NOT NULL, acides_organiques_g NUMERIC(4, 2) NOT NULL, cholesterol_mg NUMERIC(6, 2) NOT NULL, alim_grp_code INT NOT NULL, alim_ssgrp_code INT NOT NULL, alim_ssssgrp_code INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date VARCHAR(25) NOT NULL, content JSON NOT NULL COMMENT \'(DC2Type:json)\', title VARCHAR(255) NOT NULL, total_pral_index VARCHAR(255) DEFAULT NULL, INDEX IDX_3BAE0AA7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, category INT DEFAULT NULL, subcategory INT DEFAULT NULL, title VARCHAR(255) NOT NULL, instructions LONGTEXT NOT NULL, pral_index NUMERIC(6, 2) DEFAULT NULL, image LONGBLOB DEFAULT NULL, quantities JSON NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_DA88B137A76ED395 (user_id), INDEX IDX_DA88B13764C19C1 (category), INDEX IDX_DA88B137DDCA448 (subcategory), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_alim (recipe_id INT NOT NULL, alim_id INT NOT NULL, INDEX IDX_1B34EAEA59D8A214 (recipe_id), INDEX IDX_1B34EAEABF571CE (alim_id), PRIMARY KEY(recipe_id, alim_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subcategory (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_DDCA44812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(180) DEFAULT NULL, avatar VARCHAR(2048) DEFAULT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', nutrients_display_preference JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, is_archived TINYINT(1) NOT NULL, verification_token VARCHAR(64) DEFAULT NULL, token_expires_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', member_since VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649C1CC006B (verification_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
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
        $this->addSql('DROP TABLE alim');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_alim');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE subcategory');
        $this->addSql('DROP TABLE user');
    }
}
