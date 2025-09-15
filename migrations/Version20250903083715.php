<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250903083715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

     public function up(Schema $schema): void
    {
        //  добавляем колонку, а НЕ создаём таблицу заново
        $this->addSql('ALTER TABLE task ADD position INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE task DROP COLUMN position');
    }
}
