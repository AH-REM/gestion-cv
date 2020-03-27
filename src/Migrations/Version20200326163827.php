<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200326163827 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE diplome (id INT AUTO_INCREMENT NOT NULL, niveau_id INT NOT NULL, libelle VARCHAR(50) NOT NULL, INDEX IDX_EB4C4D4EB3E9C81 (niveau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE domaine (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervenant (id INT AUTO_INCREMENT NOT NULL, diplome_id INT DEFAULT NULL, emploi_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, adresse VARCHAR(100) DEFAULT NULL, cp VARCHAR(6) DEFAULT NULL, tel_fixe VARCHAR(10) DEFAULT NULL, tel_portable VARCHAR(10) DEFAULT NULL, mail VARCHAR(100) NOT NULL, divers LONGTEXT DEFAULT NULL, name_cv VARCHAR(100) DEFAULT NULL, date_maj_cv DATE DEFAULT NULL, created_at DATE NOT NULL, INDEX IDX_73D0145C26F859E2 (diplome_id), INDEX IDX_73D0145CEC013E12 (emploi_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervenant_domaine (intervenant_id INT NOT NULL, domaine_id INT NOT NULL, INDEX IDX_31B333D5AB9A1716 (intervenant_id), INDEX IDX_31B333D54272FC9F (domaine_id), PRIMARY KEY(intervenant_id, domaine_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau (id INT AUTO_INCREMENT NOT NULL, num INT NOT NULL, libelle VARCHAR(25) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_emploi (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE diplome ADD CONSTRAINT FK_EB4C4D4EB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE intervenant ADD CONSTRAINT FK_73D0145C26F859E2 FOREIGN KEY (diplome_id) REFERENCES diplome (id)');
        $this->addSql('ALTER TABLE intervenant ADD CONSTRAINT FK_73D0145CEC013E12 FOREIGN KEY (emploi_id) REFERENCES type_emploi (id)');
        $this->addSql('ALTER TABLE intervenant_domaine ADD CONSTRAINT FK_31B333D5AB9A1716 FOREIGN KEY (intervenant_id) REFERENCES intervenant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervenant_domaine ADD CONSTRAINT FK_31B333D54272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE intervenant DROP FOREIGN KEY FK_73D0145C26F859E2');
        $this->addSql('ALTER TABLE intervenant_domaine DROP FOREIGN KEY FK_31B333D54272FC9F');
        $this->addSql('ALTER TABLE intervenant_domaine DROP FOREIGN KEY FK_31B333D5AB9A1716');
        $this->addSql('ALTER TABLE diplome DROP FOREIGN KEY FK_EB4C4D4EB3E9C81');
        $this->addSql('ALTER TABLE intervenant DROP FOREIGN KEY FK_73D0145CEC013E12');
        $this->addSql('DROP TABLE diplome');
        $this->addSql('DROP TABLE domaine');
        $this->addSql('DROP TABLE intervenant');
        $this->addSql('DROP TABLE intervenant_domaine');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP TABLE type_emploi');
    }
}
