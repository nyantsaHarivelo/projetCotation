<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260115075157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__voyage AS SELECT id, tourist, acheve, paye, date_debut FROM voyage');
        $this->addSql('DROP TABLE voyage');
        $this->addSql('CREATE TABLE voyage (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, tourist VARCHAR(255) NOT NULL, acheve BOOLEAN NOT NULL, paye BOOLEAN NOT NULL, date_debut DATETIME NOT NULL, edit_user_id INTEGER DEFAULT NULL, CONSTRAINT FK_3F9D89551D90682F FOREIGN KEY (edit_user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO voyage (id, tourist, acheve, paye, date_debut) SELECT id, tourist, acheve, paye, date_debut FROM __temp__voyage');
        $this->addSql('DROP TABLE __temp__voyage');
        $this->addSql('CREATE INDEX IDX_3F9D89551D90682F ON voyage (edit_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__voyage AS SELECT id, tourist, acheve, paye, date_debut FROM voyage');
        $this->addSql('DROP TABLE voyage');
        $this->addSql('CREATE TABLE voyage (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, tourist VARCHAR(255) NOT NULL, acheve BOOLEAN NOT NULL, paye BOOLEAN NOT NULL, date_debut DATETIME NOT NULL)');
        $this->addSql('INSERT INTO voyage (id, tourist, acheve, paye, date_debut) SELECT id, tourist, acheve, paye, date_debut FROM __temp__voyage');
        $this->addSql('DROP TABLE __temp__voyage');
    }
}
