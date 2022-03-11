<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220310202359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chambre (id INT AUTO_INCREMENT NOT NULL, hotel_id INT NOT NULL, typechambre VARCHAR(255) NOT NULL, prix INT NOT NULL, description_chambre VARCHAR(255) NOT NULL, INDEX IDX_C509E4FF3243BB18 (hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, nbetoile INT NOT NULL, nbchdispo INT NOT NULL, description_hotel VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotelimage (id INT AUTO_INCREMENT NOT NULL, hotel_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_2FDDA2CC3243BB18 (hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotelreservation (id INT AUTO_INCREMENT NOT NULL, chambre_id INT DEFAULT NULL, prix VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, cratedat DATETIME DEFAULT CURRENT_TIMESTAMP, start DATETIME DEFAULT CURRENT_TIMESTAMP, end DATETIME DEFAULT CURRENT_TIMESTAMP, INDEX IDX_545753C09B177F54 (chambre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chambre ADD CONSTRAINT FK_C509E4FF3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE hotelimage ADD CONSTRAINT FK_2FDDA2CC3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE hotelreservation ADD CONSTRAINT FK_545753C09B177F54 FOREIGN KEY (chambre_id) REFERENCES chambre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotelreservation DROP FOREIGN KEY FK_545753C09B177F54');
        $this->addSql('ALTER TABLE chambre DROP FOREIGN KEY FK_C509E4FF3243BB18');
        $this->addSql('ALTER TABLE hotelimage DROP FOREIGN KEY FK_2FDDA2CC3243BB18');
        $this->addSql('DROP TABLE chambre');
        $this->addSql('DROP TABLE hotel');
        $this->addSql('DROP TABLE hotelimage');
        $this->addSql('DROP TABLE hotelreservation');
    }
}
