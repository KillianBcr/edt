<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027123642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE subject_code_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE subject_code (id INT NOT NULL, code VARCHAR(60) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE subject ADD subject_code_id INT NOT NULL');
        $this->addSql('ALTER TABLE subject DROP subject_code');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7A825852B5 FOREIGN KEY (subject_code_id) REFERENCES subject_code (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_FBCE3E7A825852B5 ON subject (subject_code_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE subject DROP CONSTRAINT FK_FBCE3E7A825852B5');
        $this->addSql('DROP SEQUENCE subject_code_id_seq CASCADE');
        $this->addSql('DROP TABLE subject_code');
        $this->addSql('DROP INDEX IDX_FBCE3E7A825852B5');
        $this->addSql('ALTER TABLE subject ADD subject_code VARCHAR(40) NOT NULL');
        $this->addSql('ALTER TABLE subject DROP subject_code_id');
    }
}
