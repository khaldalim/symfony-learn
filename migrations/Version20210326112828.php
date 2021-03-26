<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210326112828 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment_message (id INT AUTO_INCREMENT NOT NULL, message_id INT NOT NULL, created_at DATETIME NOT NULL, desctription LONGTEXT NOT NULL, INDEX IDX_45CE526F537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (msg_id INT AUTO_INCREMENT NOT NULL, topic_id INT NOT NULL, msg_created_at DATETIME NOT NULL, msg_description LONGTEXT NOT NULL, score INT NOT NULL, INDEX IDX_B6BD307F1F55203D (topic_id), PRIMARY KEY(msg_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_topic (tag_id INT NOT NULL, topic_id INT NOT NULL, INDEX IDX_BFACC71DBAD26311 (tag_id), INDEX IDX_BFACC71D1F55203D (topic_id), PRIMARY KEY(tag_id, topic_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topic (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_message ADD CONSTRAINT FK_45CE526F537A1329 FOREIGN KEY (message_id) REFERENCES message (msg_id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1F55203D FOREIGN KEY (topic_id) REFERENCES topic (id)');
        $this->addSql('ALTER TABLE tag_topic ADD CONSTRAINT FK_BFACC71DBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_topic ADD CONSTRAINT FK_BFACC71D1F55203D FOREIGN KEY (topic_id) REFERENCES topic (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_message DROP FOREIGN KEY FK_45CE526F537A1329');
        $this->addSql('ALTER TABLE tag_topic DROP FOREIGN KEY FK_BFACC71DBAD26311');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F1F55203D');
        $this->addSql('ALTER TABLE tag_topic DROP FOREIGN KEY FK_BFACC71D1F55203D');
        $this->addSql('DROP TABLE comment_message');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_topic');
        $this->addSql('DROP TABLE topic');
    }
}
