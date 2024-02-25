<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240225115012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom_categorie VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, service_id INT NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_67F068BCFB88E14F (utilisateur_id), INDEX IDX_67F068BCED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE echange_produit (id INT AUTO_INCREMENT NOT NULL, produit_in_id INT NOT NULL, produit_out_id INT NOT NULL, date_echange DATETIME NOT NULL, valide TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_AE73CFC6206519A7 (produit_in_id), UNIQUE INDEX UNIQ_AE73CFC6F35D9340 (produit_out_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE echange_service (id INT AUTO_INCREMENT NOT NULL, service_in_id INT NOT NULL, service_out_id INT NOT NULL, date_echange DATETIME NOT NULL, valide TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_664BB933B98B93D4 (service_in_id), UNIQUE INDEX UNIQ_664BB9333AC85D4C (service_out_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, titre_evenement VARCHAR(255) NOT NULL, description_evenement LONGTEXT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, UNIQUE INDEX UNIQ_B26681EF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne_commande (id INT AUTO_INCREMENT NOT NULL, panier_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_3170B74BF77D927C (panier_id), UNIQUE INDEX UNIQ_3170B74BF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, UNIQUE INDEX UNIQ_24CC0DF2FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation_evenement (id INT AUTO_INCREMENT NOT NULL, evenement_id INT NOT NULL, utilisateur_id INT NOT NULL, offre DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_65A14675FD02F13 (evenement_id), INDEX IDX_65A14675FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, utilisateur_id INT NOT NULL, wish_list_id INT DEFAULT NULL, titre_produit VARCHAR(255) NOT NULL, description_produit LONGTEXT NOT NULL, photo VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) NOT NULL, choix_echange TINYINT(1) NOT NULL, etat VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_29A5EC27BCF5E72D (categorie_id), INDEX IDX_29A5EC27FB88E14F (utilisateur_id), INDEX IDX_29A5EC27D69F3311 (wish_list_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_favoris (id INT AUTO_INCREMENT NOT NULL, wish_list_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_4FC84F4BD69F3311 (wish_list_id), UNIQUE INDEX UNIQ_4FC84F4BF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, titre_r VARCHAR(255) NOT NULL, description_r LONGTEXT NOT NULL, date DATETIME NOT NULL, INDEX IDX_CE606404FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, utilisateur_id INT NOT NULL, titre_service VARCHAR(255) NOT NULL, description_service VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, choix_echange TINYINT(1) NOT NULL, INDEX IDX_E19D9AD2BCF5E72D (categorie_id), INDEX IDX_E19D9AD2FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, score DOUBLE PRECISION DEFAULT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wish_list (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, produit_id INT NOT NULL, UNIQUE INDEX UNIQ_5B8739BDFB88E14F (utilisateur_id), INDEX IDX_5B8739BDF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE echange_produit ADD CONSTRAINT FK_AE73CFC6206519A7 FOREIGN KEY (produit_in_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE echange_produit ADD CONSTRAINT FK_AE73CFC6F35D9340 FOREIGN KEY (produit_out_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE echange_service ADD CONSTRAINT FK_664BB933B98B93D4 FOREIGN KEY (service_in_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE echange_service ADD CONSTRAINT FK_664BB9333AC85D4C FOREIGN KEY (service_out_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74BF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74BF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE participation_evenement ADD CONSTRAINT FK_65A14675FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE participation_evenement ADD CONSTRAINT FK_65A14675FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27D69F3311 FOREIGN KEY (wish_list_id) REFERENCES wish_list (id)');
        $this->addSql('ALTER TABLE produit_favoris ADD CONSTRAINT FK_4FC84F4BD69F3311 FOREIGN KEY (wish_list_id) REFERENCES wish_list (id)');
        $this->addSql('ALTER TABLE produit_favoris ADD CONSTRAINT FK_4FC84F4BF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE wish_list ADD CONSTRAINT FK_5B8739BDFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE wish_list ADD CONSTRAINT FK_5B8739BDF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCFB88E14F');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCED5CA9E6');
        $this->addSql('ALTER TABLE echange_produit DROP FOREIGN KEY FK_AE73CFC6206519A7');
        $this->addSql('ALTER TABLE echange_produit DROP FOREIGN KEY FK_AE73CFC6F35D9340');
        $this->addSql('ALTER TABLE echange_service DROP FOREIGN KEY FK_664BB933B98B93D4');
        $this->addSql('ALTER TABLE echange_service DROP FOREIGN KEY FK_664BB9333AC85D4C');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EF347EFB');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74BF77D927C');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74BF347EFB');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2FB88E14F');
        $this->addSql('ALTER TABLE participation_evenement DROP FOREIGN KEY FK_65A14675FD02F13');
        $this->addSql('ALTER TABLE participation_evenement DROP FOREIGN KEY FK_65A14675FB88E14F');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27FB88E14F');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27D69F3311');
        $this->addSql('ALTER TABLE produit_favoris DROP FOREIGN KEY FK_4FC84F4BD69F3311');
        $this->addSql('ALTER TABLE produit_favoris DROP FOREIGN KEY FK_4FC84F4BF347EFB');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404FB88E14F');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2BCF5E72D');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2FB88E14F');
        $this->addSql('ALTER TABLE wish_list DROP FOREIGN KEY FK_5B8739BDFB88E14F');
        $this->addSql('ALTER TABLE wish_list DROP FOREIGN KEY FK_5B8739BDF347EFB');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE echange_produit');
        $this->addSql('DROP TABLE echange_service');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE ligne_commande');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE participation_evenement');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE produit_favoris');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE wish_list');
    }
}
