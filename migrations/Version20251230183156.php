<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251230183156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voyage ADD COLUMN date_debut DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__voyage AS SELECT id, tourist, acheve, paye FROM voyage');
        $this->addSql('DROP TABLE voyage');
        $this->addSql('CREATE TABLE voyage (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, tourist VARCHAR(255) NOT NULL, acheve BOOLEAN NOT NULL, paye BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO voyage (id, tourist, acheve, paye) SELECT id, tourist, acheve, paye FROM __temp__voyage');
        $this->addSql('DROP TABLE __temp__voyage');
    }
}
