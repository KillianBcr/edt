<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231121084758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE years ADD start_year INT NOT NULL');
        $this->addSql('ALTER TABLE years ADD end_year INT NOT NULL');
        $this->addSql('ALTER TABLE years RENAME COLUMN season TO academic_year');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE years DROP start_year');
        $this->addSql('ALTER TABLE years DROP end_year');
        $this->addSql('ALTER TABLE years RENAME COLUMN academic_year TO season');
    }
}
