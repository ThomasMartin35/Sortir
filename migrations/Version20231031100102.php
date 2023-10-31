<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231031100102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campus (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(70) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(180) NOT NULL, postcode VARCHAR(180) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE excursion (id INT AUTO_INCREMENT NOT NULL, organizer_id INT NOT NULL, campus_id INT NOT NULL, state_id INT NOT NULL, place_id INT DEFAULT NULL, name VARCHAR(50) DEFAULT NULL, start_date DATETIME DEFAULT NULL, duration INT DEFAULT NULL, limit_registration_date DATETIME DEFAULT NULL, max_registration_number INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_9B08E72F876C4DDA (organizer_id), INDEX IDX_9B08E72FAF5D55E1 (campus_id), INDEX IDX_9B08E72F5D83CC1 (state_id), INDEX IDX_9B08E72FDA6A219 (place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE excursion_member (excursion_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_E15252D04AB4296F (excursion_id), INDEX IDX_E15252D07597D3FE (member_id), PRIMARY KEY(excursion_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `member` (id INT AUTO_INCREMENT NOT NULL, campus_id INT NOT NULL, mail VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(50) NOT NULL, first_name VARCHAR(50) NOT NULL, phone VARCHAR(25) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, is_admin TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_70E4FA785126AC48 (mail), INDEX IDX_70E4FA78AF5D55E1 (campus_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, city_id INT DEFAULT NULL, name VARCHAR(180) NOT NULL, street VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, INDEX IDX_741D53CD8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE state (id INT AUTO_INCREMENT NOT NULL, caption VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE excursion ADD CONSTRAINT FK_9B08E72F876C4DDA FOREIGN KEY (organizer_id) REFERENCES `member` (id)');
        $this->addSql('ALTER TABLE excursion ADD CONSTRAINT FK_9B08E72FAF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
        $this->addSql('ALTER TABLE excursion ADD CONSTRAINT FK_9B08E72F5D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE excursion ADD CONSTRAINT FK_9B08E72FDA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE excursion_member ADD CONSTRAINT FK_E15252D04AB4296F FOREIGN KEY (excursion_id) REFERENCES excursion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE excursion_member ADD CONSTRAINT FK_E15252D07597D3FE FOREIGN KEY (member_id) REFERENCES `member` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `member` ADD CONSTRAINT FK_70E4FA78AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CD8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE excursion DROP FOREIGN KEY FK_9B08E72F876C4DDA');
        $this->addSql('ALTER TABLE excursion DROP FOREIGN KEY FK_9B08E72FAF5D55E1');
        $this->addSql('ALTER TABLE excursion DROP FOREIGN KEY FK_9B08E72F5D83CC1');
        $this->addSql('ALTER TABLE excursion DROP FOREIGN KEY FK_9B08E72FDA6A219');
        $this->addSql('ALTER TABLE excursion_member DROP FOREIGN KEY FK_E15252D04AB4296F');
        $this->addSql('ALTER TABLE excursion_member DROP FOREIGN KEY FK_E15252D07597D3FE');
        $this->addSql('ALTER TABLE `member` DROP FOREIGN KEY FK_70E4FA78AF5D55E1');
        $this->addSql('ALTER TABLE place DROP FOREIGN KEY FK_741D53CD8BAC62AF');
        $this->addSql('DROP TABLE campus');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE excursion');
        $this->addSql('DROP TABLE excursion_member');
        $this->addSql('DROP TABLE `member`');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE state');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
