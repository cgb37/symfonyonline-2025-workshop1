<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250610105445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Add the slug column as nullable first
        $this->addSql(<<<'SQL'
            ALTER TABLE conference ADD slug VARCHAR(255)
        SQL);

        // Set a default slug for existing rows (you may want to improve this logic)
        $this->addSql(<<<'SQL'
            UPDATE conference SET slug = CONCAT(city, '-', year) WHERE slug IS NULL
        SQL);

        // Now set the column to NOT NULL
        $this->addSql(<<<'SQL'
            ALTER TABLE conference ALTER COLUMN slug SET NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE conference DROP slug
        SQL);
    }
}
