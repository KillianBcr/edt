<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231130085923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wish ADD year_id INT NOT NULL');
        $this->addSql('ALTER TABLE wish ADD CONSTRAINT FK_D7D174C940C1FEA7 FOREIGN KEY (year_id) REFERENCES years (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D7D174C940C1FEA7 ON wish (year_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE wish DROP CONSTRAINT FK_D7D174C940C1FEA7');
        $this->addSql('DROP INDEX IDX_D7D174C940C1FEA7');
        $this->addSql('ALTER TABLE wish DROP year_id');
    }
}
