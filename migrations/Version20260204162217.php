<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260204162217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contacts_page (id INT AUTO_INCREMENT NOT NULL, contact_mail VARCHAR(255) DEFAULT NULL, contact_phone VARCHAR(255) DEFAULT NULL, contact_page_text VARCHAR(255) NOT NULL, company_city VARCHAR(255) DEFAULT NULL, company_street VARCHAR(255) DEFAULT NULL, company_psc VARCHAR(255) DEFAULT NULL, company_location_lat DOUBLE PRECISION DEFAULT NULL, company_location_lng DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE seller ADD contacts_page_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE seller ADD CONSTRAINT FK_FB1AD3FC86EE6FB9 FOREIGN KEY (contacts_page_id) REFERENCES contacts_page (id)');
        $this->addSql('CREATE INDEX IDX_FB1AD3FC86EE6FB9 ON seller (contacts_page_id)');
        $this->addSql('ALTER TABLE sonata_admin_user DROP username, DROP email, DROP password, DROP roles, CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE contacts_page');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE seller DROP FOREIGN KEY FK_FB1AD3FC86EE6FB9');
        $this->addSql('DROP INDEX IDX_FB1AD3FC86EE6FB9 ON seller');
        $this->addSql('ALTER TABLE seller DROP contacts_page_id');
        $this->addSql('ALTER TABLE sonata_admin_user ADD username VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD password VARCHAR(255) NOT NULL, ADD roles JSON NOT NULL, CHANGE id id VARCHAR(255) NOT NULL');
    }
}
