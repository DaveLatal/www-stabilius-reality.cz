<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260117141405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, icon VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, seller_id INT DEFAULT NULL, INDEX IDX_4C62E6388DE820D9 (seller_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE reality (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE seller (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, position_id INT DEFAULT NULL, INDEX IDX_FB1AD3FCDD842E46 (position_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE seller_position (id INT AUTO_INCREMENT NOT NULL, position_name VARCHAR(255) NOT NULL, position_priority VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6388DE820D9 FOREIGN KEY (seller_id) REFERENCES seller (id)');
        $this->addSql('ALTER TABLE seller ADD CONSTRAINT FK_FB1AD3FCDD842E46 FOREIGN KEY (position_id) REFERENCES seller_position (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E6388DE820D9');
        $this->addSql('ALTER TABLE seller DROP FOREIGN KEY FK_FB1AD3FCDD842E46');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE reality');
        $this->addSql('DROP TABLE seller');
        $this->addSql('DROP TABLE seller_position');
    }
}
