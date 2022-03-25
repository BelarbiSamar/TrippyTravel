<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220304011056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE excursionreservation ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE excursionreservation ADD CONSTRAINT FK_4BA02EF3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4BA02EF3A76ED395 ON excursionreservation (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE excursionreservation DROP FOREIGN KEY FK_4BA02EF3A76ED395');
        $this->addSql('DROP INDEX IDX_4BA02EF3A76ED395 ON excursionreservation');
        $this->addSql('ALTER TABLE excursionreservation DROP user_id');
    }
}
