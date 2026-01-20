<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260118170130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__transport AS SELECT id, date_primus, date_terminus, primus, terminus, nombre_km, nombre_litre, prix_litre, cout_carburant, voyage_id FROM transport');
        $this->addSql('DROP TABLE transport');
        $this->addSql('CREATE TABLE transport (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date_primus DATETIME DEFAULT NULL, date_terminus DATETIME DEFAULT NULL, primus VARCHAR(50) NOT NULL, terminus VARCHAR(50) DEFAULT NULL, nombre_km DOUBLE PRECISION DEFAULT NULL, nombre_litre DOUBLE PRECISION DEFAULT NULL, prix_litre DOUBLE PRECISION DEFAULT NULL, cout_transport DOUBLE PRECISION DEFAULT NULL, voyage_id INTEGER DEFAULT NULL, CONSTRAINT FK_66AB212E68C9E5AF FOREIGN KEY (voyage_id) REFERENCES voyage (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO transport (id, date_primus, date_terminus, primus, terminus, nombre_km, nombre_litre, prix_litre, cout_transport, voyage_id) SELECT id, date_primus, date_terminus, primus, terminus, nombre_km, nombre_litre, prix_litre, cout_carburant, voyage_id FROM __temp__transport');
        $this->addSql('DROP TABLE __temp__transport');
        $this->addSql('CREATE INDEX IDX_66AB212E68C9E5AF ON transport (voyage_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__transport AS SELECT id, date_primus, date_terminus, primus, terminus, nombre_km, nombre_litre, prix_litre, cout_transport, voyage_id FROM transport');
        $this->addSql('DROP TABLE transport');
        $this->addSql('CREATE TABLE transport (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date_primus DATETIME DEFAULT NULL, date_terminus DATETIME DEFAULT NULL, primus VARCHAR(50) NOT NULL, terminus VARCHAR(50) DEFAULT NULL, nombre_km DOUBLE PRECISION DEFAULT NULL, nombre_litre DOUBLE PRECISION DEFAULT NULL, prix_litre DOUBLE PRECISION DEFAULT NULL, cout_carburant DOUBLE PRECISION DEFAULT NULL, voyage_id INTEGER DEFAULT NULL, CONSTRAINT FK_66AB212E68C9E5AF FOREIGN KEY (voyage_id) REFERENCES voyage (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO transport (id, date_primus, date_terminus, primus, terminus, nombre_km, nombre_litre, prix_litre, cout_carburant, voyage_id) SELECT id, date_primus, date_terminus, primus, terminus, nombre_km, nombre_litre, prix_litre, cout_transport, voyage_id FROM __temp__transport');
        $this->addSql('DROP TABLE __temp__transport');
        $this->addSql('CREATE INDEX IDX_66AB212E68C9E5AF ON transport (voyage_id)');
    }
}
