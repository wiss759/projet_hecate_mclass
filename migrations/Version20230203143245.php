<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230203143245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_user (category_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_608AC0E12469DE2 (category_id), INDEX IDX_608AC0EA76ED395 (user_id), PRIMARY KEY(category_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE open_hours (id INT AUTO_INCREMENT NOT NULL, start_hours DATETIME NOT NULL, end_hours DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(6) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_open_hours (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, open_hours_id INT NOT NULL, user_has_booked_id INT DEFAULT NULL, category_id INT NOT NULL, is_booked TINYINT(1) NOT NULL, INDEX IDX_66C05B679D86650F (user_id_id), INDEX IDX_66C05B6777CF38C (open_hours_id), INDEX IDX_66C05B673127775A (user_has_booked_id), INDEX IDX_66C05B6712469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_user ADD CONSTRAINT FK_608AC0E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_user ADD CONSTRAINT FK_608AC0EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_open_hours ADD CONSTRAINT FK_66C05B679D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_open_hours ADD CONSTRAINT FK_66C05B6777CF38C FOREIGN KEY (open_hours_id) REFERENCES open_hours (id)');
        $this->addSql('ALTER TABLE user_open_hours ADD CONSTRAINT FK_66C05B673127775A FOREIGN KEY (user_has_booked_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_open_hours ADD CONSTRAINT FK_66C05B6712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_user DROP FOREIGN KEY FK_608AC0E12469DE2');
        $this->addSql('ALTER TABLE category_user DROP FOREIGN KEY FK_608AC0EA76ED395');
        $this->addSql('ALTER TABLE user_open_hours DROP FOREIGN KEY FK_66C05B679D86650F');
        $this->addSql('ALTER TABLE user_open_hours DROP FOREIGN KEY FK_66C05B6777CF38C');
        $this->addSql('ALTER TABLE user_open_hours DROP FOREIGN KEY FK_66C05B673127775A');
        $this->addSql('ALTER TABLE user_open_hours DROP FOREIGN KEY FK_66C05B6712469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_user');
        $this->addSql('DROP TABLE open_hours');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_open_hours');
    }
}
