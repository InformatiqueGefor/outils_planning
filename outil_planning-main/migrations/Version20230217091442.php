<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230217091442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conges ADD session_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE conges ADD CONSTRAINT FK_6327DE3A613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('CREATE INDEX IDX_6327DE3A613FECDF ON conges (session_id)');
        $this->addSql('ALTER TABLE examens ADD session_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE examens ADD CONSTRAINT FK_B2E32DD7613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('CREATE INDEX IDX_B2E32DD7613FECDF ON examens (session_id)');
        $this->addSql('ALTER TABLE session ADD formation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D45200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('CREATE INDEX IDX_D044D5D45200282E ON session (formation_id)');
        $this->addSql('ALTER TABLE stage ADD session_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C9369613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('CREATE INDEX IDX_C27C9369613FECDF ON stage (session_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conges DROP FOREIGN KEY FK_6327DE3A613FECDF');
        $this->addSql('DROP INDEX IDX_6327DE3A613FECDF ON conges');
        $this->addSql('ALTER TABLE conges DROP session_id');
        $this->addSql('ALTER TABLE examens DROP FOREIGN KEY FK_B2E32DD7613FECDF');
        $this->addSql('DROP INDEX IDX_B2E32DD7613FECDF ON examens');
        $this->addSql('ALTER TABLE examens DROP session_id');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D45200282E');
        $this->addSql('DROP INDEX IDX_D044D5D45200282E ON session');
        $this->addSql('ALTER TABLE session DROP formation_id');
        $this->addSql('ALTER TABLE stage DROP FOREIGN KEY FK_C27C9369613FECDF');
        $this->addSql('DROP INDEX IDX_C27C9369613FECDF ON stage');
        $this->addSql('ALTER TABLE stage DROP session_id');
    }
}
