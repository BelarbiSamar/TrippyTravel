<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220223014331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE excursion (id INT AUTO_INCREMENT NOT NULL, excursioncategorie_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, programme LONGTEXT NOT NULL, ville VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_9B08E72F83498997 (excursioncategorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE excursioncategorie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE excursionimage (id INT AUTO_INCREMENT NOT NULL, excursion_id INT DEFAULT NULL, image_name VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_51A2C7624AB4296F (excursion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE excursion ADD CONSTRAINT FK_9B08E72F83498997 FOREIGN KEY (excursioncategorie_id) REFERENCES excursioncategorie (id)');
        $this->addSql('ALTER TABLE excursionimage ADD CONSTRAINT FK_51A2C7624AB4296F FOREIGN KEY (excursion_id) REFERENCES excursion (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE excursionimage DROP FOREIGN KEY FK_51A2C7624AB4296F');
        $this->addSql('ALTER TABLE excursion DROP FOREIGN KEY FK_9B08E72F83498997');
        $this->addSql('DROP TABLE excursion');
        $this->addSql('DROP TABLE excursioncategorie');
        $this->addSql('DROP TABLE excursionimage');
    }
}
