<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190315144323 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tls_scan_result CHANGE date_valid_from date_valid_from DATETIME DEFAULT NULL, CHANGE date_valid_to date_valid_to DATETIME DEFAULT NULL, CHANGE raw_tls_data raw_tls_data JSON DEFAULT NULL, CHANGE fail_reason fail_reason VARCHAR(1024) DEFAULT NULL');
        $this->addSql('ALTER TABLE website ADD port INT DEFAULT NULL, CHANGE ip ip VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE website_readonly_result ADD port INT DEFAULT NULL, CHANGE ip ip VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE scheduled_command CHANGE arguments arguments VARCHAR(250) DEFAULT NULL, CHANGE cron_expression cron_expression VARCHAR(100) DEFAULT NULL, CHANGE last_return_code last_return_code INT DEFAULT NULL, CHANGE log_file log_file VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scheduled_command CHANGE arguments arguments VARCHAR(250) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE cron_expression cron_expression VARCHAR(100) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_return_code last_return_code INT DEFAULT NULL, CHANGE log_file log_file VARCHAR(100) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE tls_scan_result CHANGE date_valid_from date_valid_from DATETIME DEFAULT \'NULL\', CHANGE date_valid_to date_valid_to DATETIME DEFAULT \'NULL\', CHANGE raw_tls_data raw_tls_data LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin, CHANGE fail_reason fail_reason VARCHAR(1024) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE website DROP port, CHANGE ip ip VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE website_readonly_result DROP port, CHANGE ip ip VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
