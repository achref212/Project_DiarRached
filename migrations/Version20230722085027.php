<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230722085027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appartement (id INT AUTO_INCREMENT NOT NULL, residence_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, etage VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, surface DOUBLE PRECISION NOT NULL, etat VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_71A6BD8D8B225FBD (residence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, residence_id INT DEFAULT NULL, appartement_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, INDEX IDX_42C849558B225FBD (residence_id), INDEX IDX_42C84955E1729BBA (appartement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE residence (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appartement ADD CONSTRAINT FK_71A6BD8D8B225FBD FOREIGN KEY (residence_id) REFERENCES residence (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849558B225FBD FOREIGN KEY (residence_id) REFERENCES residence (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955E1729BBA FOREIGN KEY (appartement_id) REFERENCES appartement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appartement DROP FOREIGN KEY FK_71A6BD8D8B225FBD');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849558B225FBD');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955E1729BBA');
        $this->addSql('DROP TABLE appartement');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE residence');
    }
}
