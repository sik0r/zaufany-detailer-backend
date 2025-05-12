<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250510205920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE address (id UUID NOT NULL, region_id UUID NOT NULL, city_id UUID NOT NULL, street VARCHAR(255) NOT NULL, postal_code VARCHAR(6) NOT NULL, region_name VARCHAR(100) NOT NULL, region_slug VARCHAR(120) NOT NULL, city_name VARCHAR(255) NOT NULL, city_slug VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D4E6F8198260155 ON address (region_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D4E6F818BAC62AF ON address (city_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX address_region_city_idx ON address (region_name, city_name)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN address.id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN address.region_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN address.city_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD CONSTRAINT FK_D4E6F8198260155 FOREIGN KEY (region_id) REFERENCES voivodeship (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD CONSTRAINT FK_D4E6F818BAC62AF FOREIGN KEY (city_id) REFERENCES locality (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address DROP CONSTRAINT FK_D4E6F8198260155
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address DROP CONSTRAINT FK_D4E6F818BAC62AF
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE address
        SQL);
    }
}
