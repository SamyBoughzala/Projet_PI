<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303110131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, nom1 VARCHAR(255) DEFAULT NULL, nom2 VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contract (id INT AUTO_INCREMENT NOT NULL, signature_id VARCHAR(255) DEFAULT NULL, document_id VARCHAR(255) DEFAULT NULL, signer_id VARCHAR(255) DEFAULT NULL, pdf_sans_signature VARCHAR(255) DEFAULT NULL, nom1 VARCHAR(255) DEFAULT NULL, nom2 VARCHAR(255) DEFAULT NULL, produit_in_nom VARCHAR(255) DEFAULT NULL, produit_out_nom VARCHAR(255) DEFAULT NULL, photo_in VARCHAR(255) DEFAULT NULL, photo_out VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE contract');
    }
}
