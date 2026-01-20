<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260111143655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__vol AS SELECT id, reference, primus, terminus, date_primus, date_terminus, agence_vol FROM vol');
        $this->addSql('DROP TABLE vol');
        $this->addSql('CREATE TABLE vol (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, reference VARCHAR(50) NOT NULL, primus VARCHAR(50) DEFAULT NULL, terminus VARCHAR(50) DEFAULT NULL, date_primus DATETIME DEFAULT NULL, date_terminus DATETIME DEFAULT NULL, agence_vol VARCHAR(50) DEFAULT NULL)');
        $this->addSql('INSERT INTO vol (id, reference, primus, terminus, date_primus, date_terminus, agence_vol) SELECT id, reference, primus, terminus, date_primus, date_terminus, agence_vol FROM __temp__vol');
        $this->addSql('DROP TABLE __temp__vol');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__vol AS SELECT id, reference, primus, terminus, date_primus, date_terminus, agence_vol FROM vol');
        $this->addSql('DROP TABLE vol');
        $this->addSql('CREATE TABLE vol (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, reference VARCHAR(255) NOT NULL, primus VARCHAR(255) DEFAULT NULL, terminus VARCHAR(50) DEFAULT NULL, date_primus DATETIME DEFAULT NULL, date_terminus DATETIME DEFAULT NULL, agence_vol VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO vol (id, reference, primus, terminus, date_primus, date_terminus, agence_vol) SELECT id, reference, primus, terminus, date_primus, date_terminus, agence_vol FROM __temp__vol');
        $this->addSql('DROP TABLE __temp__vol');
    }
}
