<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250407110234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE category_id category_id INT NOT NULL, CHANGE prix prix DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE category MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON category');
        $this->addSql('ALTER TABLE category CHANGE id category_id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE category ADD PRIMARY KEY (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE category_id category_id INT DEFAULT NULL, CHANGE prix prix VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE category MODIFY category_id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON category');
        $this->addSql('ALTER TABLE category CHANGE category_id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE category ADD PRIMARY KEY (id)');
    }
}
