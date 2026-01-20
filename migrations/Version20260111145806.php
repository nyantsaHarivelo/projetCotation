<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260111145806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transport (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date_primus DATETIME DEFAULT NULL, date_terminus DATETIME DEFAULT NULL, primus VARCHAR(50) NOT NULL, terminus VARCHAR(50) DEFAULT NULL, nombre_km DOUBLE PRECISION DEFAULT NULL, nombre_litre DOUBLE PRECISION DEFAULT NULL, prix_litre DOUBLE PRECISION DEFAULT NULL, cout_carburant DOUBLE PRECISION DEFAULT NULL)');
        $this->addSql('ALTER TABLE prestation ADD COLUMN lieu_prestation VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE transport');
        $this->addSql('CREATE TEMPORARY TABLE __temp__prestation AS SELECT id, prestation, montant, date_prestation, voyage_id FROM prestation');
        $this->addSql('DROP TABLE prestation');
        $this->addSql('CREATE TABLE prestation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, prestation VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, date_prestation DATETIME NOT NULL, voyage_id INTEGER NOT NULL, CONSTRAINT FK_51C88FAD68C9E5AF FOREIGN KEY (voyage_id) REFERENCES voyage (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO prestation (id, prestation, montant, date_prestation, voyage_id) SELECT id, prestation, montant, date_prestation, voyage_id FROM __temp__prestation');
        $this->addSql('DROP TABLE __temp__prestation');
        $this->addSql('CREATE INDEX IDX_51C88FAD68C9E5AF ON prestation (voyage_id)');
    }
}
