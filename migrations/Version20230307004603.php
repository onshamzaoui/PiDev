<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307004603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE don DROP FOREIGN KEY FK_F8F081D969545666');
        $this->addSql('ALTER TABLE bonus DROP FOREIGN KEY FK_9F987F7AA76ED395');
        $this->addSql('DROP TABLE bonus');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP INDEX IDX_F8F081D969545666 ON don');
        $this->addSql('ALTER TABLE don DROP bonus_id');
        $this->addSql('ALTER TABLE user ADD disable TINYINT(1) DEFAULT NULL, DROP points');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bonus (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, points INT DEFAULT NULL, date_attribution DATETIME NOT NULL, INDEX IDX_9F987F7AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, headers LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, queue_name VARCHAR(190) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), INDEX IDX_75EA56E0FB7336F0 (queue_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE bonus ADD CONSTRAINT FK_9F987F7AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE don ADD bonus_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE don ADD CONSTRAINT FK_F8F081D969545666 FOREIGN KEY (bonus_id) REFERENCES bonus (id)');
        $this->addSql('CREATE INDEX IDX_F8F081D969545666 ON don (bonus_id)');
        $this->addSql('ALTER TABLE user ADD points INT NOT NULL, DROP disable');
    }
}
