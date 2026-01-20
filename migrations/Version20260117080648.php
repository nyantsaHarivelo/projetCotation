<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260117080648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demande_user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(50) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, profil VARCHAR(255) DEFAULT NULL, nom VARCHAR(50) NOT NULL, prenoms VARCHAR(50) DEFAULT NULL, profession VARCHAR(50) NOT NULL, adresse VARCHAR(255) NOT NULL, numero_tel VARCHAR(20) DEFAULT NULL, mail VARCHAR(255) DEFAULT NULL)');
        $this->addSql('ALTER TABLE user ADD COLUMN mail VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE demande_user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, roles, password, profil, nom, prenoms, profession, adresse, numero_tel FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, profil VARCHAR(255) DEFAULT NULL, nom VARCHAR(50) NOT NULL, prenoms VARCHAR(50) DEFAULT NULL, profession VARCHAR(50) NOT NULL, adresse VARCHAR(50) NOT NULL, numero_tel VARCHAR(20) DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, username, roles, password, profil, nom, prenoms, profession, adresse, numero_tel) SELECT id, username, roles, password, profil, nom, prenoms, profession, adresse, numero_tel FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON user (username)');
    }
}
