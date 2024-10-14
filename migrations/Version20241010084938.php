<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241010084938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE objet DROP FOREIGN KEY FK_46CD4C386B82600');
        $this->addSql('DROP INDEX IDX_46CD4C386B82600 ON objet');
        $this->addSql('ALTER TABLE objet DROP proprio_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE objet ADD proprio_id INT NOT NULL');
        $this->addSql('ALTER TABLE objet ADD CONSTRAINT FK_46CD4C386B82600 FOREIGN KEY (proprio_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_46CD4C386B82600 ON objet (proprio_id)');
    }
}
