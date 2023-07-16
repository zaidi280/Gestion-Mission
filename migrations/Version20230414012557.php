<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230414012557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, dep VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profession (id INT AUTO_INCREMENT NOT NULL, grade VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande_mission ADD profession_id INT DEFAULT NULL, ADD departement_id INT DEFAULT NULL, ADD ordvalid TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE demande_mission ADD CONSTRAINT FK_A0904993FDEF8996 FOREIGN KEY (profession_id) REFERENCES profession (id)');
        $this->addSql('ALTER TABLE demande_mission ADD CONSTRAINT FK_A0904993CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('CREATE INDEX IDX_A0904993FDEF8996 ON demande_mission (profession_id)');
        $this->addSql('CREATE INDEX IDX_A0904993CCF9E01E ON demande_mission (departement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_mission DROP FOREIGN KEY FK_A0904993CCF9E01E');
        $this->addSql('ALTER TABLE demande_mission DROP FOREIGN KEY FK_A0904993FDEF8996');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE profession');
        $this->addSql('DROP INDEX IDX_A0904993FDEF8996 ON demande_mission');
        $this->addSql('DROP INDEX IDX_A0904993CCF9E01E ON demande_mission');
        $this->addSql('ALTER TABLE demande_mission DROP profession_id, DROP departement_id, DROP ordvalid');
    }
}
