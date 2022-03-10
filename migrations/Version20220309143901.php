<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220309143901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hotelreservation (id INT AUTO_INCREMENT NOT NULL, chambre_id INT DEFAULT NULL, prix VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, cratedat DATETIME DEFAULT CURRENT_TIMESTAMP, start DATETIME DEFAULT CURRENT_TIMESTAMP, end DATETIME DEFAULT CURRENT_TIMESTAMP, INDEX IDX_545753C09B177F54 (chambre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hotelreservation ADD CONSTRAINT FK_545753C09B177F54 FOREIGN KEY (chambre_id) REFERENCES chambre (id)');
        $this->addSql('ALTER TABLE hotel CHANGE nbchdispo nbchdispo INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE hotelreservation');
        $this->addSql('ALTER TABLE chambre CHANGE typechambre typechambre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description_chambre description_chambre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE hotel CHANGE libelle libelle VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE localisation localisation VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nbchdispo nbchdispo INT DEFAULT NULL, CHANGE description_hotel description_hotel VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE hotelimage CHANGE image image VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
