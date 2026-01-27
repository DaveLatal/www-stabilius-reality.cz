<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260120191749 extends AbstractMigration
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
        $this->addSql('ALTER TABLE sonata_admin_user ADD username VARCHAR(255) NOT NULL, ADD password VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD roles JSON NOT NULL, ADD created_at VARCHAR(255) NOT NULL, CHANGE id id VARCHAR(255) NOT NULL');
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
        $this->addSql('ALTER TABLE sonata_admin_user DROP username, DROP password, DROP email, DROP roles, DROP created_at, CHANGE id id VARCHAR(255) NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`');
    }
}
