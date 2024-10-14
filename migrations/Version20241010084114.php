<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241010084114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE objet ADD CONSTRAINT FK_46CD4C386B82600 FOREIGN KEY (proprio_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_46CD4C386B82600 ON objet (proprio_id)');
        $this->addSql('ALTER TABLE objet RENAME INDEX fk_46cd4c38a76ed395 TO IDX_46CD4C38A76ED395');
        $this->addSql('ALTER TABLE user ADD nom VARCHAR(255) NOT NULL, ADD prenom VARCHAR(255) NOT NULL, ADD points INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE objet DROP FOREIGN KEY FK_46CD4C386B82600');
        $this->addSql('DROP INDEX IDX_46CD4C386B82600 ON objet');
        $this->addSql('ALTER TABLE objet RENAME INDEX idx_46cd4c38a76ed395 TO FK_46CD4C38A76ED395');
        $this->addSql('ALTER TABLE user DROP nom, DROP prenom, DROP points');
    }
}
