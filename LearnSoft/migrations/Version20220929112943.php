<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220929112943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classe (id INT AUTO_INCREMENT NOT NULL, nom_classe VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eleve (id INT AUTO_INCREMENT NOT NULL, parents_id INT NOT NULL, date_naissance VARCHAR(255) NOT NULL, INDEX IDX_ECA105F7B706B6D3 (parents_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eleve_evaluation (id INT AUTO_INCREMENT NOT NULL, evaluation_id INT NOT NULL, eleve_id INT NOT NULL, note INT DEFAULT NULL, temps_mis VARCHAR(60) DEFAULT NULL, begin_at VARCHAR(255) DEFAULT NULL, end_at VARCHAR(255) DEFAULT NULL, progression INT DEFAULT NULL, etat TINYINT(1) DEFAULT NULL, reprise_at VARCHAR(255) DEFAULT NULL, tentative_restante INT NOT NULL, correction TINYINT(1) DEFAULT NULL, duree_restant VARCHAR(255) DEFAULT NULL, note_initiale INT NOT NULL, INDEX IDX_11194A27456C5646 (evaluation_id), INDEX IDX_11194A27A6CC7B2 (eleve_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eleve_proposition (id INT AUTO_INCREMENT NOT NULL, eleve_id INT NOT NULL, propositions_id INT NOT NULL, sujet_id INT NOT NULL, reponse_eleve TINYINT(1) NOT NULL, note_recu DOUBLE PRECISION NOT NULL, INDEX IDX_42AAC964A6CC7B2 (eleve_id), INDEX IDX_42AAC964F0CBBA09 (propositions_id), INDEX IDX_42AAC9647C4D497E (sujet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eleve_sujet (id INT AUTO_INCREMENT NOT NULL, eleve_id INT NOT NULL, sujet_id INT NOT NULL, evaluation_id INT NOT NULL, reponse VARCHAR(255) DEFAULT NULL, etat_reponse INT DEFAULT NULL, etat TINYINT(1) NOT NULL, note_recu DOUBLE PRECISION DEFAULT NULL, apport VARCHAR(300) DEFAULT NULL, INDEX IDX_31734474A6CC7B2 (eleve_id), INDEX IDX_317344747C4D497E (sujet_id), INDEX IDX_31734474456C5646 (evaluation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emploi_temps (id INT AUTO_INCREMENT NOT NULL, parent_id INT NOT NULL, heure_debut VARCHAR(255) NOT NULL, heure_fin VARCHAR(255) NOT NULL, jour VARCHAR(255) NOT NULL, titre_event VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, classe INT NOT NULL, INDEX IDX_50D1B05E727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluation (id INT AUTO_INCREMENT NOT NULL, parents_id INT NOT NULL, classe_id INT NOT NULL, matiere_id INT NOT NULL, type_evaluation_id INT DEFAULT NULL, titre VARCHAR(60) NOT NULL, description VARCHAR(255) NOT NULL, date_debut VARCHAR(255) NOT NULL, date_fin VARCHAR(255) NOT NULL, heure_debut VARCHAR(255) NOT NULL, heure_fin VARCHAR(255) NOT NULL, statut TINYINT(1) NOT NULL, etat VARCHAR(255) NOT NULL, duree VARCHAR(60) DEFAULT NULL, tentative INT NOT NULL, note_sur INT NOT NULL, niveau_evaluation INT NOT NULL, ouvre_dans VARCHAR(60) DEFAULT NULL, overdue TINYINT(1) DEFAULT NULL, ferme_dans VARCHAR(60) DEFAULT NULL, INDEX IDX_1323A575B706B6D3 (parents_id), INDEX IDX_1323A5758F5EA509 (classe_id), INDEX IDX_1323A575F46CD258 (matiere_id), INDEX IDX_1323A5753581E173 (type_evaluation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, classe_id INT NOT NULL, nom_matiere VARCHAR(255) NOT NULL, INDEX IDX_9014574A8F5EA509 (classe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parents (id INT AUTO_INCREMENT NOT NULL, profession VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE progression_jour (id INT AUTO_INCREMENT NOT NULL, eleve_id INT NOT NULL, matiere_id INT NOT NULL, date_du_jour VARCHAR(255) NOT NULL, progression INT NOT NULL, INDEX IDX_C8D82747A6CC7B2 (eleve_id), INDEX IDX_C8D82747F46CD258 (matiere_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE propositions (id INT AUTO_INCREMENT NOT NULL, sujet_id INT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, reponse_valide TINYINT(1) NOT NULL, INDEX IDX_E9AB02867C4D497E (sujet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, type_role VARCHAR(55) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sujet (id INT AUTO_INCREMENT NOT NULL, evaluation_id INT NOT NULL, question VARCHAR(255) NOT NULL, notation INT NOT NULL, etat TINYINT(1) DEFAULT NULL, type_evaluation TINYINT(1) NOT NULL, nombre INT DEFAULT NULL, INDEX IDX_2E13599D456C5646 (evaluation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_evaluation (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(60) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, eleve_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, role_id INT DEFAULT NULL, classe_id INT DEFAULT NULL, username VARCHAR(55) NOT NULL, nom VARCHAR(55) NOT NULL, prenom VARCHAR(55) NOT NULL, email VARCHAR(55) NOT NULL, contact INT NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, sexe VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, dtype VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3F85E0677 (username), UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), INDEX IDX_1D1C63B3A6CC7B2 (eleve_id), INDEX IDX_1D1C63B3727ACA70 (parent_id), INDEX IDX_1D1C63B3D60322AC (role_id), INDEX IDX_1D1C63B38F5EA509 (classe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F7B706B6D3 FOREIGN KEY (parents_id) REFERENCES parents (id)');
        $this->addSql('ALTER TABLE eleve_evaluation ADD CONSTRAINT FK_11194A27456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluation (id)');
        $this->addSql('ALTER TABLE eleve_evaluation ADD CONSTRAINT FK_11194A27A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id)');
        $this->addSql('ALTER TABLE eleve_proposition ADD CONSTRAINT FK_42AAC964A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id)');
        $this->addSql('ALTER TABLE eleve_proposition ADD CONSTRAINT FK_42AAC964F0CBBA09 FOREIGN KEY (propositions_id) REFERENCES propositions (id)');
        $this->addSql('ALTER TABLE eleve_proposition ADD CONSTRAINT FK_42AAC9647C4D497E FOREIGN KEY (sujet_id) REFERENCES sujet (id)');
        $this->addSql('ALTER TABLE eleve_sujet ADD CONSTRAINT FK_31734474A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id)');
        $this->addSql('ALTER TABLE eleve_sujet ADD CONSTRAINT FK_317344747C4D497E FOREIGN KEY (sujet_id) REFERENCES sujet (id)');
        $this->addSql('ALTER TABLE eleve_sujet ADD CONSTRAINT FK_31734474456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluation (id)');
        $this->addSql('ALTER TABLE emploi_temps ADD CONSTRAINT FK_50D1B05E727ACA70 FOREIGN KEY (parent_id) REFERENCES parents (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A575B706B6D3 FOREIGN KEY (parents_id) REFERENCES parents (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A5758F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A575F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A5753581E173 FOREIGN KEY (type_evaluation_id) REFERENCES type_evaluation (id)');
        $this->addSql('ALTER TABLE matiere ADD CONSTRAINT FK_9014574A8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE progression_jour ADD CONSTRAINT FK_C8D82747A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id)');
        $this->addSql('ALTER TABLE progression_jour ADD CONSTRAINT FK_C8D82747F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE propositions ADD CONSTRAINT FK_E9AB02867C4D497E FOREIGN KEY (sujet_id) REFERENCES sujet (id)');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT FK_2E13599D456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluation (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3727ACA70 FOREIGN KEY (parent_id) REFERENCES parents (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3D60322AC FOREIGN KEY (role_id) REFERENCES roles (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B38F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A5758F5EA509');
        $this->addSql('ALTER TABLE matiere DROP FOREIGN KEY FK_9014574A8F5EA509');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B38F5EA509');
        $this->addSql('ALTER TABLE eleve_evaluation DROP FOREIGN KEY FK_11194A27A6CC7B2');
        $this->addSql('ALTER TABLE eleve_proposition DROP FOREIGN KEY FK_42AAC964A6CC7B2');
        $this->addSql('ALTER TABLE eleve_sujet DROP FOREIGN KEY FK_31734474A6CC7B2');
        $this->addSql('ALTER TABLE progression_jour DROP FOREIGN KEY FK_C8D82747A6CC7B2');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3A6CC7B2');
        $this->addSql('ALTER TABLE eleve_evaluation DROP FOREIGN KEY FK_11194A27456C5646');
        $this->addSql('ALTER TABLE eleve_sujet DROP FOREIGN KEY FK_31734474456C5646');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY FK_2E13599D456C5646');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A575F46CD258');
        $this->addSql('ALTER TABLE progression_jour DROP FOREIGN KEY FK_C8D82747F46CD258');
        $this->addSql('ALTER TABLE eleve DROP FOREIGN KEY FK_ECA105F7B706B6D3');
        $this->addSql('ALTER TABLE emploi_temps DROP FOREIGN KEY FK_50D1B05E727ACA70');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A575B706B6D3');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3727ACA70');
        $this->addSql('ALTER TABLE eleve_proposition DROP FOREIGN KEY FK_42AAC964F0CBBA09');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3D60322AC');
        $this->addSql('ALTER TABLE eleve_proposition DROP FOREIGN KEY FK_42AAC9647C4D497E');
        $this->addSql('ALTER TABLE eleve_sujet DROP FOREIGN KEY FK_317344747C4D497E');
        $this->addSql('ALTER TABLE propositions DROP FOREIGN KEY FK_E9AB02867C4D497E');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A5753581E173');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP TABLE eleve');
        $this->addSql('DROP TABLE eleve_evaluation');
        $this->addSql('DROP TABLE eleve_proposition');
        $this->addSql('DROP TABLE eleve_sujet');
        $this->addSql('DROP TABLE emploi_temps');
        $this->addSql('DROP TABLE evaluation');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE parents');
        $this->addSql('DROP TABLE progression_jour');
        $this->addSql('DROP TABLE propositions');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE sujet');
        $this->addSql('DROP TABLE type_evaluation');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
