<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260110085328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__prestation AS SELECT id, prestation, montant, date_prestation FROM prestation');
        $this->addSql('DROP TABLE prestation');
        $this->addSql('CREATE TABLE prestation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, prestation VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, date_prestation DATETIME NOT NULL, voyage_id INTEGER NOT NULL, CONSTRAINT FK_51C88FAD68C9E5AF FOREIGN KEY (voyage_id) REFERENCES voyage (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO prestation (id, prestation, montant, date_prestation) SELECT id, prestation, montant, date_prestation FROM __temp__prestation');
        $this->addSql('DROP TABLE __temp__prestation');
        $this->addSql('CREATE INDEX IDX_51C88FAD68C9E5AF ON prestation (voyage_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__prestation AS SELECT id, prestation, montant, date_prestation FROM prestation');
        $this->addSql('DROP TABLE prestation');
        $this->addSql('CREATE TABLE prestation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, prestation VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, date_prestation DATETIME NOT NULL)');
        $this->addSql('INSERT INTO prestation (id, prestation, montant, date_prestation) SELECT id, prestation, montant, date_prestation FROM __temp__prestation');
        $this->addSql('DROP TABLE __temp__prestation');
    }
}
