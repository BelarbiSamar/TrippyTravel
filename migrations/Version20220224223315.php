<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220224223315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE excursionreservation ADD excursion_id INT NOT NULL');
        $this->addSql('ALTER TABLE excursionreservation ADD CONSTRAINT FK_4BA02EF34AB4296F FOREIGN KEY (excursion_id) REFERENCES excursion (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4BA02EF34AB4296F ON excursionreservation (excursion_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE excursionreservation DROP FOREIGN KEY FK_4BA02EF34AB4296F');
        $this->addSql('DROP INDEX UNIQ_4BA02EF34AB4296F ON excursionreservation');
        $this->addSql('ALTER TABLE excursionreservation DROP excursion_id');
    }
}
