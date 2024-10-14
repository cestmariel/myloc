<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241007122132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE objet (id INT AUTO_INCREMENT NOT NULL, proprietaire_id INT NOT NULL, categorie_id INT NOT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_46CD4C3876C50E4A (proprietaire_id), INDEX IDX_46CD4C38BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE objet ADD CONSTRAINT FK_46CD4C3876C50E4A FOREIGN KEY (proprietaire_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE objet ADD CONSTRAINT FK_46CD4C38BCF5E72D FOREIGN KEY (categorie_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE objet DROP FOREIGN KEY FK_46CD4C3876C50E4A');
        $this->addSql('ALTER TABLE objet DROP FOREIGN KEY FK_46CD4C38BCF5E72D');
        $this->addSql('DROP TABLE objet');
    }
}
