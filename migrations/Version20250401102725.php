<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250401102725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE training (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE training_session (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, training_id INT DEFAULT NULL, date DATETIME NOT NULL, sets INT NOT NULL, reps INT NOT NULL, weight DOUBLE PRECISION NOT NULL, INDEX IDX_D7A45DAA76ED395 (user_id), INDEX IDX_D7A45DABEFD98D1 (training_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE training_session ADD CONSTRAINT FK_D7A45DAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE training_session ADD CONSTRAINT FK_D7A45DABEFD98D1 FOREIGN KEY (training_id) REFERENCES training (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_session DROP FOREIGN KEY FK_D7A45DAA76ED395');
        $this->addSql('ALTER TABLE training_session DROP FOREIGN KEY FK_D7A45DABEFD98D1');
        $this->addSql('DROP TABLE training');
        $this->addSql('DROP TABLE training_session');
    }
}
