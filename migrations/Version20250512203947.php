<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250512203947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE workshop (id UUID NOT NULL, address_id UUID NOT NULL, company_id UUID NOT NULL, name VARCHAR(240) NOT NULL, slug VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, opening_hours JSON NOT NULL, phone VARCHAR(32) DEFAULT NULL, email VARCHAR(255) NOT NULL, thumbnail VARCHAR(255) DEFAULT NULL, gallery JSON NOT NULL, is_published BOOLEAN DEFAULT false NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_9B6F02C4989D9B62 ON workshop (slug)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_9B6F02C4F5B7AF75 ON workshop (address_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9B6F02C4979B1AD6 ON workshop (company_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN workshop.id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN workshop.address_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN workshop.company_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN workshop.created_at IS '(DC2Type:datetimetz_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN workshop.updated_at IS '(DC2Type:datetimetz_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE workshop_service (service_id UUID NOT NULL, PRIMARY KEY(service_id))
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN workshop_service.service_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE workshop_meta (id UUID NOT NULL, workshop_id UUID NOT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_ED753D721FDCE57C ON workshop_meta (workshop_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN workshop_meta.id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN workshop_meta.workshop_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE workshop_price_list_item (id UUID NOT NULL, workshop_id UUID NOT NULL, service_id UUID NOT NULL, price VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8366CF4E1FDCE57C ON workshop_price_list_item (workshop_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8366CF4EED5CA9E6 ON workshop_price_list_item (service_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN workshop_price_list_item.id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN workshop_price_list_item.workshop_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN workshop_price_list_item.service_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop ADD CONSTRAINT FK_9B6F02C4F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop ADD CONSTRAINT FK_9B6F02C4979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop_meta ADD CONSTRAINT FK_ED753D721FDCE57C FOREIGN KEY (workshop_id) REFERENCES workshop (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop_price_list_item ADD CONSTRAINT FK_8366CF4E1FDCE57C FOREIGN KEY (workshop_id) REFERENCES workshop (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop_price_list_item ADD CONSTRAINT FK_8366CF4EED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop DROP CONSTRAINT FK_9B6F02C4F5B7AF75
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop DROP CONSTRAINT FK_9B6F02C4979B1AD6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop_meta DROP CONSTRAINT FK_ED753D721FDCE57C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop_price_list_item DROP CONSTRAINT FK_8366CF4E1FDCE57C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop_price_list_item DROP CONSTRAINT FK_8366CF4EED5CA9E6
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE workshop
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE workshop_service
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE workshop_meta
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE workshop_price_list_item
        SQL);
    }
}
