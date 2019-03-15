<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190315143535 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tls_scan_result ADD status VARCHAR(255) NOT NULL, DROP is_valid, CHANGE date_valid_from date_valid_from DATETIME DEFAULT NULL, CHANGE date_valid_to date_valid_to DATETIME DEFAULT NULL, CHANGE raw_tls_data raw_tls_data JSON DEFAULT NULL, CHANGE fail_reason fail_reason VARCHAR(1024) DEFAULT NULL');
        $this->addSql('ALTER TABLE website CHANGE ip ip VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE website_readonly_result CHANGE ip ip VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE scheduled_command CHANGE arguments arguments VARCHAR(250) DEFAULT NULL, CHANGE cron_expression cron_expression VARCHAR(100) DEFAULT NULL, CHANGE last_return_code last_return_code INT DEFAULT NULL, CHANGE log_file log_file VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scheduled_command CHANGE arguments arguments VARCHAR(250) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE cron_expression cron_expression VARCHAR(100) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_return_code last_return_code INT DEFAULT NULL, CHANGE log_file log_file VARCHAR(100) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE tls_scan_result ADD is_valid TINYINT(1) NOT NULL, DROP status, CHANGE date_valid_from date_valid_from DATETIME DEFAULT \'NULL\', CHANGE date_valid_to date_valid_to DATETIME DEFAULT \'NULL\', CHANGE raw_tls_data raw_tls_data LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin, CHANGE fail_reason fail_reason VARCHAR(1024) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE website CHANGE ip ip VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE website_readonly_result CHANGE ip ip VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
