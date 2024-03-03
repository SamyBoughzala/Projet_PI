<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229225334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnee (id INT AUTO_INCREMENT NOT NULL, troqueur_id INT NOT NULL, abonnee_id INT NOT NULL, INDEX IDX_5211BACDBB8D60AC (troqueur_id), INDEX IDX_5211BACD8BACA6B1 (abonnee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_favoris (id INT AUTO_INCREMENT NOT NULL, wish_list_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_4FC84F4BD69F3311 (wish_list_id), UNIQUE INDEX UNIQ_4FC84F4BF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abonnee ADD CONSTRAINT FK_5211BACDBB8D60AC FOREIGN KEY (troqueur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE abonnee ADD CONSTRAINT FK_5211BACD8BACA6B1 FOREIGN KEY (abonnee_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE produit_favoris ADD CONSTRAINT FK_4FC84F4BD69F3311 FOREIGN KEY (wish_list_id) REFERENCES wish_list (id)');
        $this->addSql('ALTER TABLE produit_favoris ADD CONSTRAINT FK_4FC84F4BF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27D69F3311');
        $this->addSql('DROP INDEX IDX_29A5EC27D69F3311 ON produit');
        $this->addSql('ALTER TABLE produit DROP wish_list_id');
        $this->addSql('ALTER TABLE wish_list DROP FOREIGN KEY FK_5B8739BDF347EFB');
        $this->addSql('DROP INDEX IDX_5B8739BDF347EFB ON wish_list');
        $this->addSql('ALTER TABLE wish_list DROP produit_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnee DROP FOREIGN KEY FK_5211BACDBB8D60AC');
        $this->addSql('ALTER TABLE abonnee DROP FOREIGN KEY FK_5211BACD8BACA6B1');
        $this->addSql('ALTER TABLE produit_favoris DROP FOREIGN KEY FK_4FC84F4BD69F3311');
        $this->addSql('ALTER TABLE produit_favoris DROP FOREIGN KEY FK_4FC84F4BF347EFB');
        $this->addSql('DROP TABLE abonnee');
        $this->addSql('DROP TABLE produit_favoris');
        $this->addSql('ALTER TABLE produit ADD wish_list_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27D69F3311 FOREIGN KEY (wish_list_id) REFERENCES wish_list (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27D69F3311 ON produit (wish_list_id)');
        $this->addSql('ALTER TABLE wish_list ADD produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE wish_list ADD CONSTRAINT FK_5B8739BDF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_5B8739BDF347EFB ON wish_list (produit_id)');
    }
}
