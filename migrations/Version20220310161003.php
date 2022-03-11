<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220310161003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attraction (id INT AUTO_INCREMENT NOT NULL, id_categorie_id INT DEFAULT NULL, libelle VARCHAR(25) NOT NULL, localisation VARCHAR(30) DEFAULT NULL, horraire VARCHAR(30) DEFAULT NULL, prix INT NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_D503E6B89F34925F (id_categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attraction_reservation (id INT AUTO_INCREMENT NOT NULL, attraction_id INT DEFAULT NULL, prix VARCHAR(255) NOT NULL, status VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, start DATETIME DEFAULT CURRENT_TIMESTAMP, end DATETIME DEFAULT CURRENT_TIMESTAMP, INDEX IDX_14B002203C216F9D (attraction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_attraction (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(20) NOT NULL, contrainte_age TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, type_id INT NOT NULL, object VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(255) NOT NULL, seen TINYINT(1) NOT NULL, INDEX IDX_CE60640419EB6921 (client_id), INDEX IDX_CE606404C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_rec (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, bio LONGTEXT DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, naissance DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', sexe VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attraction ADD CONSTRAINT FK_D503E6B89F34925F FOREIGN KEY (id_categorie_id) REFERENCES categorie_attraction (id)');
        $this->addSql('ALTER TABLE attraction_reservation ADD CONSTRAINT FK_14B002203C216F9D FOREIGN KEY (attraction_id) REFERENCES attraction (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640419EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404C54C8C93 FOREIGN KEY (type_id) REFERENCES type_rec (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attraction_reservation DROP FOREIGN KEY FK_14B002203C216F9D');
        $this->addSql('ALTER TABLE attraction DROP FOREIGN KEY FK_D503E6B89F34925F');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404C54C8C93');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640419EB6921');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE attraction');
        $this->addSql('DROP TABLE attraction_reservation');
        $this->addSql('DROP TABLE categorie_attraction');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE type_rec');
        $this->addSql('DROP TABLE user');
    }
}
