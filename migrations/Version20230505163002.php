<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230505163002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id_user INT DEFAULT NULL, ArtId INT AUTO_INCREMENT NOT NULL, ArtLib VARCHAR(20) NOT NULL, ArtDesc VARCHAR(200) NOT NULL, ArtDispo INT NOT NULL, ArtImg VARCHAR(255) NOT NULL, QrCode VARCHAR(255) DEFAULT NULL, ArtPrix DOUBLE PRECISION NOT NULL, CatLib VARCHAR(20) NOT NULL, note INT DEFAULT NULL, INDEX fk_id_user (id_user), UNIQUE INDEX ArtLib (ArtLib), PRIMARY KEY(ArtId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE basket (id INT AUTO_INCREMENT NOT NULL, id_article INT DEFAULT NULL, id_client INT DEFAULT NULL, date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX id_article (id_article), INDEX id_client (id_client), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (CatId INT AUTO_INCREMENT NOT NULL, CatLib VARCHAR(20) NOT NULL, PRIMARY KEY(CatId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE command_articles (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, command_id INT DEFAULT NULL, INDEX fk_art (article_id), INDEX fk_com (command_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commands (id INT AUTO_INCREMENT NOT NULL, id_client INT DEFAULT NULL, date_commande DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, mode_livraison VARCHAR(30) DEFAULT NULL, mode_paiement VARCHAR(30) DEFAULT NULL, cout_totale DOUBLE PRECISION DEFAULT NULL, etat_commande VARCHAR(30) DEFAULT \'En attente\', adresse VARCHAR(30) NOT NULL, INDEX id_client (id_client), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id VARCHAR(255) NOT NULL, id_user INT DEFAULT NULL, TypeR VARCHAR(255) NOT NULL, dateR DATETIME NOT NULL, etat VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, objet VARCHAR(255) NOT NULL, INDEX IDX_CE6064046B3CA4B (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, reclamation_id VARCHAR(255) NOT NULL, date_rep DATETIME NOT NULL, contenu_rep VARCHAR(255) NOT NULL, INDEX IDX_5FB6DEC72D6BA2D9 (reclamation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id_user INT AUTO_INCREMENT NOT NULL, password VARCHAR(50) NOT NULL, mail VARCHAR(50) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, adresse VARCHAR(100) NOT NULL, role VARCHAR(30) NOT NULL, cin VARCHAR(8) NOT NULL, numero VARCHAR(50) NOT NULL, UNIQUE INDEX id_user (id_user), UNIQUE INDEX mail (mail), UNIQUE INDEX cin (cin), PRIMARY KEY(id_user)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E666B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id_user)');
        $this->addSql('ALTER TABLE basket ADD CONSTRAINT FK_2246507BDCA7A716 FOREIGN KEY (id_article) REFERENCES article (ArtId)');
        $this->addSql('ALTER TABLE basket ADD CONSTRAINT FK_2246507BE173B1B8 FOREIGN KEY (id_client) REFERENCES utilisateur (id_user)');
        $this->addSql('ALTER TABLE command_articles ADD CONSTRAINT FK_8631E8F97294869C FOREIGN KEY (article_id) REFERENCES article (ArtId)');
        $this->addSql('ALTER TABLE command_articles ADD CONSTRAINT FK_8631E8F933E1689A FOREIGN KEY (command_id) REFERENCES commands (id)');
        $this->addSql('ALTER TABLE commands ADD CONSTRAINT FK_9A3E132CE173B1B8 FOREIGN KEY (id_client) REFERENCES utilisateur (id_user)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id_user)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC72D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E666B3CA4B');
        $this->addSql('ALTER TABLE basket DROP FOREIGN KEY FK_2246507BDCA7A716');
        $this->addSql('ALTER TABLE basket DROP FOREIGN KEY FK_2246507BE173B1B8');
        $this->addSql('ALTER TABLE command_articles DROP FOREIGN KEY FK_8631E8F97294869C');
        $this->addSql('ALTER TABLE command_articles DROP FOREIGN KEY FK_8631E8F933E1689A');
        $this->addSql('ALTER TABLE commands DROP FOREIGN KEY FK_9A3E132CE173B1B8');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046B3CA4B');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC72D6BA2D9');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE basket');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE command_articles');
        $this->addSql('DROP TABLE commands');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
