<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250405204659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trophy (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, training_session_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_112AFAE9A76ED395 (user_id), INDEX IDX_112AFAE9DB8156B9 (training_session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trophy ADD CONSTRAINT FK_112AFAE9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE trophy ADD CONSTRAINT FK_112AFAE9DB8156B9 FOREIGN KEY (training_session_id) REFERENCES training_session (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trophy DROP FOREIGN KEY FK_112AFAE9A76ED395');
        $this->addSql('ALTER TABLE trophy DROP FOREIGN KEY FK_112AFAE9DB8156B9');
        $this->addSql('DROP TABLE trophy');
    }
}
