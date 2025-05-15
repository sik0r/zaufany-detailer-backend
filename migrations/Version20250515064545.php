<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250515064545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE url_workshop (id UUID NOT NULL, workshop_id UUID NOT NULL, url VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_F90F6C4DF47645AE ON url_workshop (url)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_F90F6C4D1FDCE57C ON url_workshop (workshop_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN url_workshop.id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN url_workshop.workshop_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN url_workshop.created_at IS '(DC2Type:datetimetz_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN url_workshop.updated_at IS '(DC2Type:datetimetz_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE url_workshop ADD CONSTRAINT FK_F90F6C4D1FDCE57C FOREIGN KEY (workshop_id) REFERENCES workshop (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE url_workshop DROP CONSTRAINT FK_F90F6C4D1FDCE57C
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE url_workshop
        SQL);
    }
}
