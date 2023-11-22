<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231122091542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE week_group (week_id INT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(week_id, group_id))');
        $this->addSql('CREATE INDEX IDX_A66B8D46C86F3B2F ON week_group (week_id)');
        $this->addSql('CREATE INDEX IDX_A66B8D46FE54D947 ON week_group (group_id)');
        $this->addSql('ALTER TABLE week_group ADD CONSTRAINT FK_A66B8D46C86F3B2F FOREIGN KEY (week_id) REFERENCES week (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE week_group ADD CONSTRAINT FK_A66B8D46FE54D947 FOREIGN KEY (group_id) REFERENCES groups (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE week_group DROP CONSTRAINT FK_A66B8D46C86F3B2F');
        $this->addSql('ALTER TABLE week_group DROP CONSTRAINT FK_A66B8D46FE54D947');
        $this->addSql('DROP TABLE week_group');
    }
}
