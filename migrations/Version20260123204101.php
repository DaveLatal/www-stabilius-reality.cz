<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260123204101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, category_id INT DEFAULT NULL, INDEX IDX_64C19C112469DE2 (category_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, icon VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, seller_id INT DEFAULT NULL, INDEX IDX_4C62E6388DE820D9 (seller_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE reality (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE reality_gallery (id INT AUTO_INCREMENT NOT NULL, reality_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_51301533FB0B9425 (reality_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE reality_gallery_item (id INT AUTO_INCREMENT NOT NULL, image_name VARCHAR(255) NOT NULL, image_size INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, gallery_id INT DEFAULT NULL, INDEX IDX_44B3B97B4E7AF8F (gallery_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE seller (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, position_id INT DEFAULT NULL, INDEX IDX_FB1AD3FCDD842E46 (position_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE seller_position (id INT AUTO_INCREMENT NOT NULL, position_name VARCHAR(255) NOT NULL, position_priority VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE sonata_admin_user (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C112469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6388DE820D9 FOREIGN KEY (seller_id) REFERENCES seller (id)');
        $this->addSql('ALTER TABLE reality_gallery ADD CONSTRAINT FK_51301533FB0B9425 FOREIGN KEY (reality_id) REFERENCES reality (id)');
        $this->addSql('ALTER TABLE reality_gallery_item ADD CONSTRAINT FK_44B3B97B4E7AF8F FOREIGN KEY (gallery_id) REFERENCES reality_gallery (id)');
        $this->addSql('ALTER TABLE seller ADD CONSTRAINT FK_FB1AD3FCDD842E46 FOREIGN KEY (position_id) REFERENCES seller_position (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C112469DE2');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E6388DE820D9');
        $this->addSql('ALTER TABLE reality_gallery DROP FOREIGN KEY FK_51301533FB0B9425');
        $this->addSql('ALTER TABLE reality_gallery_item DROP FOREIGN KEY FK_44B3B97B4E7AF8F');
        $this->addSql('ALTER TABLE seller DROP FOREIGN KEY FK_FB1AD3FCDD842E46');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE reality');
        $this->addSql('DROP TABLE reality_gallery');
        $this->addSql('DROP TABLE reality_gallery_item');
        $this->addSql('DROP TABLE seller');
        $this->addSql('DROP TABLE seller_position');
        $this->addSql('DROP TABLE sonata_admin_user');
    }
}
