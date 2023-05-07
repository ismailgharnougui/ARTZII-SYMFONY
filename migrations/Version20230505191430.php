<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230505191430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livreur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(20) NOT NULL, prenom VARCHAR(20) NOT NULL, region VARCHAR(20) NOT NULL, telephone VARCHAR(20) NOT NULL, etat VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE livraison CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE livreur_id livreur_id INT NOT NULL, CHANGE etat_liv etat_liv VARCHAR(20) NOT NULL, CHANGE date_liv date_liv DATETIME NOT NULL, CHANGE commande_id commande_id INT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1FF8646701 FOREIGN KEY (livreur_id) REFERENCES livreur (id)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1F82EA2E54 FOREIGN KEY (commande_id) REFERENCES commands (id)');
        $this->addSql('CREATE INDEX IDX_A60C9F1FF8646701 ON livraison (livreur_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A60C9F1F82EA2E54 ON livraison (commande_id)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY fk_user_recl');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY fk_user_recl');
        $this->addSql('ALTER TABLE reclamation CHANGE id id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id_user)');
        $this->addSql('DROP INDEX fk_user_recl ON reclamation');
        $this->addSql('CREATE INDEX IDX_CE6064046B3CA4B ON reclamation (id_user)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT fk_user_recl FOREIGN KEY (id_user) REFERENCES utilisateur (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY fk_bc');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY fk_bc');
        $this->addSql('ALTER TABLE reponse CHANGE reclamation_id reclamation_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC72D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id)');
        $this->addSql('DROP INDEX reclamation_id ON reponse');
        $this->addSql('CREATE INDEX IDX_5FB6DEC72D6BA2D9 ON reponse (reclamation_id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT fk_bc FOREIGN KEY (reclamation_id) REFERENCES reclamation (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1FF8646701');
        $this->addSql('DROP TABLE livreur');
        $this->addSql('ALTER TABLE livraison MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1F82EA2E54');
        $this->addSql('DROP INDEX IDX_A60C9F1FF8646701 ON livraison');
        $this->addSql('DROP INDEX UNIQ_A60C9F1F82EA2E54 ON livraison');
        $this->addSql('DROP INDEX `primary` ON livraison');
        $this->addSql('ALTER TABLE livraison CHANGE id id INT NOT NULL, CHANGE livreur_id livreur_id INT DEFAULT NULL, CHANGE commande_id commande_id INT DEFAULT NULL, CHANGE etat_liv etat_liv TINYINT(1) NOT NULL, CHANGE date_liv date_liv DATE NOT NULL');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046B3CA4B');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046B3CA4B');
        $this->addSql('ALTER TABLE reclamation CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT fk_user_recl FOREIGN KEY (id_user) REFERENCES utilisateur (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_ce6064046b3ca4b ON reclamation');
        $this->addSql('CREATE INDEX fk_user_recl ON reclamation (id_user)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id_user)');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC72D6BA2D9');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC72D6BA2D9');
        $this->addSql('ALTER TABLE reponse CHANGE reclamation_id reclamation_id INT NOT NULL');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT fk_bc FOREIGN KEY (reclamation_id) REFERENCES reclamation (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_5fb6dec72d6ba2d9 ON reponse');
        $this->addSql('CREATE INDEX reclamation_id ON reponse (reclamation_id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC72D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id)');
    }
}
