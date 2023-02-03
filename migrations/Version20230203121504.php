<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230203121504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_open_hours ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_open_hours ADD CONSTRAINT FK_66C05B6712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_66C05B6712469DE2 ON user_open_hours (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_open_hours DROP FOREIGN KEY FK_66C05B6712469DE2');
        $this->addSql('DROP INDEX IDX_66C05B6712469DE2 ON user_open_hours');
        $this->addSql('ALTER TABLE user_open_hours DROP category_id');
    }
}
