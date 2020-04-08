<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200407065007 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, branche_id INT DEFAULT NULL, user_id INT DEFAULT NULL, theme VARCHAR(255) NOT NULL, deroule LONGTEXT NOT NULL, created_at DATETIME DEFAULT NULL, date_debut VARCHAR(255) DEFAULT NULL, date_fin VARCHAR(255) DEFAULT NULL, INDEX IDX_50159CA99DDF9A9E (branche_id), INDEX IDX_50159CA9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet_domaine (projet_id INT NOT NULL, domaine_id INT NOT NULL, INDEX IDX_FA8557BDC18272 (projet_id), INDEX IDX_FA8557BD4272FC9F (domaine_id), PRIMARY KEY(projet_id, domaine_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA99DDF9A9E FOREIGN KEY (branche_id) REFERENCES branche (id)');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE projet_domaine ADD CONSTRAINT FK_FA8557BDC18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projet_domaine ADD CONSTRAINT FK_FA8557BD4272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE projet_domaine DROP FOREIGN KEY FK_FA8557BDC18272');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE projet_domaine');
    }
}
