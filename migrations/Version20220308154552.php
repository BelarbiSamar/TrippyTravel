<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220308154552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articlimages ADD article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE articlimages ADD CONSTRAINT FK_22A181277294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_22A181277294869C ON articlimages (article_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articlimages DROP FOREIGN KEY FK_22A181277294869C');
        $this->addSql('DROP INDEX IDX_22A181277294869C ON articlimages');
        $this->addSql('ALTER TABLE articlimages DROP article_id');
    }
}
