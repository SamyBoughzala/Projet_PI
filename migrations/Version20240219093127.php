<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240219093127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wish_list DROP FOREIGN KEY FK_5B8739BDF347EFB');
        $this->addSql('DROP INDEX IDX_5B8739BDF347EFB ON wish_list');
        $this->addSql('ALTER TABLE wish_list DROP produit_id, DROP wish_list_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wish_list ADD produit_id INT NOT NULL, ADD wish_list_id INT NOT NULL');
        $this->addSql('ALTER TABLE wish_list ADD CONSTRAINT FK_5B8739BDF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_5B8739BDF347EFB ON wish_list (produit_id)');
    }
}
