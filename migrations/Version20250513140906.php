<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250513140906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop_service DROP CONSTRAINT workshop_service_pkey
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop_service ADD workshop_id UUID NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN workshop_service.workshop_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop_service ADD CONSTRAINT FK_A3D288C5ED5CA9E6 FOREIGN KEY (service_id) REFERENCES workshop (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop_service ADD CONSTRAINT FK_A3D288C51FDCE57C FOREIGN KEY (workshop_id) REFERENCES service (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A3D288C5ED5CA9E6 ON workshop_service (service_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A3D288C51FDCE57C ON workshop_service (workshop_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop_service ADD PRIMARY KEY (service_id, workshop_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop_service DROP CONSTRAINT FK_A3D288C5ED5CA9E6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop_service DROP CONSTRAINT FK_A3D288C51FDCE57C
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_A3D288C5ED5CA9E6
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_A3D288C51FDCE57C
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX workshop_service_pkey
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop_service DROP workshop_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop_service ADD PRIMARY KEY (service_id)
        SQL);
    }
}
