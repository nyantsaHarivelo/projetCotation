<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260111151359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hebergement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom_hebergement VARCHAR(50) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME DEFAULT NULL, lieu_hebergement VARCHAR(50) NOT NULL, cout_hebergement DOUBLE PRECISION NOT NULL)');
        $this->addSql('DROP TABLE herbergement');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE herbergement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom_hebergement VARCHAR(50) NOT NULL COLLATE "BINARY", date_debut DATETIME NOT NULL, date_fin DATETIME DEFAULT NULL, lieu_hebergement VARCHAR(50) NOT NULL COLLATE "BINARY", cout_hebergement DOUBLE PRECISION NOT NULL)');
        $this->addSql('DROP TABLE hebergement');
    }
}
