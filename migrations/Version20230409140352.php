<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230409140352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employe ADD user_id INT DEFAULT NULL, ADD supprimer TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F804D3B9A76ED395 ON employe (user_id)');
        $this->addSql('ALTER TABLE user ADD supprimer TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B9A76ED395');
        $this->addSql('DROP INDEX IDX_F804D3B9A76ED395 ON employe');
        $this->addSql('ALTER TABLE employe DROP user_id, DROP supprimer');
        $this->addSql('ALTER TABLE user DROP supprimer');
    }
}
