<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190315101324 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE scheduled_command (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, command VARCHAR(100) NOT NULL, arguments VARCHAR(250) DEFAULT NULL, cron_expression VARCHAR(100) DEFAULT NULL, last_execution DATETIME NOT NULL, last_return_code INT DEFAULT NULL, log_file VARCHAR(100) DEFAULT NULL, priority INT NOT NULL, execute_immediately TINYINT(1) NOT NULL, disabled TINYINT(1) NOT NULL, locked TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tls_scan_result CHANGE date_valid_from date_valid_from DATETIME DEFAULT NULL, CHANGE date_valid_to date_valid_to DATETIME DEFAULT NULL, CHANGE raw_tls_data raw_tls_data JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE website CHANGE ip ip VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE website_readonly_result CHANGE ip ip VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE scheduled_command');
        $this->addSql('ALTER TABLE tls_scan_result CHANGE date_valid_from date_valid_from DATETIME DEFAULT \'NULL\', CHANGE date_valid_to date_valid_to DATETIME DEFAULT \'NULL\', CHANGE raw_tls_data raw_tls_data LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE website CHANGE ip ip VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE website_readonly_result CHANGE ip ip VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
