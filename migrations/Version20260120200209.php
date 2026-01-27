<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260120200209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category CHANGE name name VARCHAR(255) NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE contact CHANGE icon icon VARCHAR(255) NOT NULL, CHANGE value value VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reality CHANGE title title VARCHAR(255) NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reality_gallery_item CHANGE image_name image_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE seller CHANGE first_name first_name VARCHAR(255) NOT NULL, CHANGE surname surname VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE seller_position CHANGE position_name position_name VARCHAR(255) NOT NULL, CHANGE position_priority position_priority VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE sonata_admin_user DROP created_at, CHANGE id id VARCHAR(255) NOT NULL, CHANGE username username VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, CHANGE slug slug VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`');
        $this->addSql('ALTER TABLE contact CHANGE icon icon VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, CHANGE value value VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`');
        $this->addSql('ALTER TABLE reality CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, CHANGE slug slug VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`');
        $this->addSql('ALTER TABLE reality_gallery_item CHANGE image_name image_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`');
        $this->addSql('ALTER TABLE seller CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, CHANGE surname surname VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`');
        $this->addSql('ALTER TABLE seller_position CHANGE position_name position_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, CHANGE position_priority position_priority VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`');
        $this->addSql('ALTER TABLE sonata_admin_user ADD created_at VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, CHANGE id id VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, CHANGE username username VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`');
    }
}
