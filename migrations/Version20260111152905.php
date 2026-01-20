<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260111152905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__hebergement AS SELECT id, nom_hebergement, date_debut, date_fin, lieu_hebergement, cout_hebergement FROM hebergement');
        $this->addSql('DROP TABLE hebergement');
        $this->addSql('CREATE TABLE hebergement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom_hebergement VARCHAR(50) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME DEFAULT NULL, lieu_hebergement VARCHAR(50) NOT NULL, cout_hebergement DOUBLE PRECISION NOT NULL, voyage_id INTEGER NOT NULL, CONSTRAINT FK_4852DD9C68C9E5AF FOREIGN KEY (voyage_id) REFERENCES voyage (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO hebergement (id, nom_hebergement, date_debut, date_fin, lieu_hebergement, cout_hebergement) SELECT id, nom_hebergement, date_debut, date_fin, lieu_hebergement, cout_hebergement FROM __temp__hebergement');
        $this->addSql('DROP TABLE __temp__hebergement');
        $this->addSql('CREATE INDEX IDX_4852DD9C68C9E5AF ON hebergement (voyage_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__transport AS SELECT id, date_primus, date_terminus, primus, terminus, nombre_km, nombre_litre, prix_litre, cout_carburant FROM transport');
        $this->addSql('DROP TABLE transport');
        $this->addSql('CREATE TABLE transport (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date_primus DATETIME DEFAULT NULL, date_terminus DATETIME DEFAULT NULL, primus VARCHAR(50) NOT NULL, terminus VARCHAR(50) DEFAULT NULL, nombre_km DOUBLE PRECISION DEFAULT NULL, nombre_litre DOUBLE PRECISION DEFAULT NULL, prix_litre DOUBLE PRECISION DEFAULT NULL, cout_carburant DOUBLE PRECISION DEFAULT NULL, voyage_id INTEGER DEFAULT NULL, CONSTRAINT FK_66AB212E68C9E5AF FOREIGN KEY (voyage_id) REFERENCES voyage (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO transport (id, date_primus, date_terminus, primus, terminus, nombre_km, nombre_litre, prix_litre, cout_carburant) SELECT id, date_primus, date_terminus, primus, terminus, nombre_km, nombre_litre, prix_litre, cout_carburant FROM __temp__transport');
        $this->addSql('DROP TABLE __temp__transport');
        $this->addSql('CREATE INDEX IDX_66AB212E68C9E5AF ON transport (voyage_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__vol AS SELECT id, reference, primus, terminus, date_primus, date_terminus, agence_vol FROM vol');
        $this->addSql('DROP TABLE vol');
        $this->addSql('CREATE TABLE vol (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, reference VARCHAR(50) NOT NULL, primus VARCHAR(50) DEFAULT NULL, terminus VARCHAR(50) DEFAULT NULL, date_primus DATETIME DEFAULT NULL, date_terminus DATETIME DEFAULT NULL, agence_vol VARCHAR(50) DEFAULT NULL, voyage_id INTEGER NOT NULL, CONSTRAINT FK_95C97EB68C9E5AF FOREIGN KEY (voyage_id) REFERENCES voyage (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO vol (id, reference, primus, terminus, date_primus, date_terminus, agence_vol) SELECT id, reference, primus, terminus, date_primus, date_terminus, agence_vol FROM __temp__vol');
        $this->addSql('DROP TABLE __temp__vol');
        $this->addSql('CREATE INDEX IDX_95C97EB68C9E5AF ON vol (voyage_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__hebergement AS SELECT id, nom_hebergement, date_debut, date_fin, lieu_hebergement, cout_hebergement FROM hebergement');
        $this->addSql('DROP TABLE hebergement');
        $this->addSql('CREATE TABLE hebergement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom_hebergement VARCHAR(50) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME DEFAULT NULL, lieu_hebergement VARCHAR(50) NOT NULL, cout_hebergement DOUBLE PRECISION NOT NULL)');
        $this->addSql('INSERT INTO hebergement (id, nom_hebergement, date_debut, date_fin, lieu_hebergement, cout_hebergement) SELECT id, nom_hebergement, date_debut, date_fin, lieu_hebergement, cout_hebergement FROM __temp__hebergement');
        $this->addSql('DROP TABLE __temp__hebergement');
        $this->addSql('CREATE TEMPORARY TABLE __temp__transport AS SELECT id, date_primus, date_terminus, primus, terminus, nombre_km, nombre_litre, prix_litre, cout_carburant FROM transport');
        $this->addSql('DROP TABLE transport');
        $this->addSql('CREATE TABLE transport (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date_primus DATETIME DEFAULT NULL, date_terminus DATETIME DEFAULT NULL, primus VARCHAR(50) NOT NULL, terminus VARCHAR(50) DEFAULT NULL, nombre_km DOUBLE PRECISION DEFAULT NULL, nombre_litre DOUBLE PRECISION DEFAULT NULL, prix_litre DOUBLE PRECISION DEFAULT NULL, cout_carburant DOUBLE PRECISION DEFAULT NULL)');
        $this->addSql('INSERT INTO transport (id, date_primus, date_terminus, primus, terminus, nombre_km, nombre_litre, prix_litre, cout_carburant) SELECT id, date_primus, date_terminus, primus, terminus, nombre_km, nombre_litre, prix_litre, cout_carburant FROM __temp__transport');
        $this->addSql('DROP TABLE __temp__transport');
        $this->addSql('CREATE TEMPORARY TABLE __temp__vol AS SELECT id, reference, primus, terminus, date_primus, date_terminus, agence_vol FROM vol');
        $this->addSql('DROP TABLE vol');
        $this->addSql('CREATE TABLE vol (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, reference VARCHAR(50) NOT NULL, primus VARCHAR(50) DEFAULT NULL, terminus VARCHAR(50) DEFAULT NULL, date_primus DATETIME DEFAULT NULL, date_terminus DATETIME DEFAULT NULL, agence_vol VARCHAR(50) DEFAULT NULL)');
        $this->addSql('INSERT INTO vol (id, reference, primus, terminus, date_primus, date_terminus, agence_vol) SELECT id, reference, primus, terminus, date_primus, date_terminus, agence_vol FROM __temp__vol');
        $this->addSql('DROP TABLE __temp__vol');
    }
}
