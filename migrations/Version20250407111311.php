<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250407111311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6612469DE2');
        $this->addSql('ALTER TABLE article CHANGE prix prix DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6612469DE2 FOREIGN KEY (category_id) REFERENCES category (category_id)');
        $this->addSql('ALTER TABLE category MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON category');
        $this->addSql('ALTER TABLE category CHANGE id category_id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE category ADD PRIMARY KEY (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6612469DE2');
        $this->addSql('ALTER TABLE article CHANGE prix prix VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE category MODIFY category_id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON category');
        $this->addSql('ALTER TABLE category CHANGE category_id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE category ADD PRIMARY KEY (id)');
    }
}
