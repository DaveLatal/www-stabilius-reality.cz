<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260117144647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reality_gallery (id INT AUTO_INCREMENT NOT NULL, reality_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_51301533FB0B9425 (reality_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE reality_gallery_item (id INT AUTO_INCREMENT NOT NULL, image_name VARCHAR(255) NOT NULL, image_size INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, gallery_id INT DEFAULT NULL, INDEX IDX_44B3B97B4E7AF8F (gallery_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE reality_gallery ADD CONSTRAINT FK_51301533FB0B9425 FOREIGN KEY (reality_id) REFERENCES reality (id)');
        $this->addSql('ALTER TABLE reality_gallery_item ADD CONSTRAINT FK_44B3B97B4E7AF8F FOREIGN KEY (gallery_id) REFERENCES reality_gallery (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reality_gallery DROP FOREIGN KEY FK_51301533FB0B9425');
        $this->addSql('ALTER TABLE reality_gallery_item DROP FOREIGN KEY FK_44B3B97B4E7AF8F');
        $this->addSql('DROP TABLE reality_gallery');
        $this->addSql('DROP TABLE reality_gallery_item');
    }
}
