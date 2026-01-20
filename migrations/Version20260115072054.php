<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260115072054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, username, roles, password) SELECT id, username, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON user (username)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__voyage AS SELECT id, tourist, acheve, paye, date_debut FROM voyage');
        $this->addSql('DROP TABLE voyage');
        $this->addSql('CREATE TABLE voyage (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, tourist VARCHAR(255) NOT NULL, acheve BOOLEAN NOT NULL, paye BOOLEAN NOT NULL, date_debut DATETIME NOT NULL, edit_user_id INTEGER NOT NULL, CONSTRAINT FK_3F9D89551D90682F FOREIGN KEY (edit_user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO voyage (id, tourist, acheve, paye, date_debut) SELECT id, tourist, acheve, paye, date_debut FROM __temp__voyage');
        $this->addSql('DROP TABLE __temp__voyage');
        $this->addSql('CREATE INDEX IDX_3F9D89551D90682F ON voyage (edit_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD COLUMN profil VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE TEMPORARY TABLE __temp__voyage AS SELECT id, tourist, acheve, paye, date_debut FROM voyage');
        $this->addSql('DROP TABLE voyage');
        $this->addSql('CREATE TABLE voyage (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, tourist VARCHAR(255) NOT NULL, acheve BOOLEAN NOT NULL, paye BOOLEAN NOT NULL, date_debut DATETIME NOT NULL)');
        $this->addSql('INSERT INTO voyage (id, tourist, acheve, paye, date_debut) SELECT id, tourist, acheve, paye, date_debut FROM __temp__voyage');
        $this->addSql('DROP TABLE __temp__voyage');
    }
}
