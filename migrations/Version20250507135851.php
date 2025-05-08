<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507135851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE products_sub_category (products_id INT NOT NULL, sub_category_id INT NOT NULL, INDEX IDX_DBC460366C8A81A9 (products_id), INDEX IDX_DBC46036F7BFE87C (sub_category_id), PRIMARY KEY(products_id, sub_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sub_categories (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE products_sub_category ADD CONSTRAINT FK_DBC460366C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE products_sub_category ADD CONSTRAINT FK_DBC46036F7BFE87C FOREIGN KEY (sub_category_id) REFERENCES sub_category (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE products_sub_category DROP FOREIGN KEY FK_DBC460366C8A81A9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE products_sub_category DROP FOREIGN KEY FK_DBC46036F7BFE87C
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE products_sub_category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE sub_categories
        SQL);
    }
}
