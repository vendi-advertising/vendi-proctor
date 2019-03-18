<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190316185728 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tls_scan_result (id INT AUTO_INCREMENT NOT NULL, website_id INT NOT NULL, date_valid_from DATETIME DEFAULT NULL, date_valid_to DATETIME DEFAULT NULL, raw_tls_data JSON DEFAULT NULL, date_time_created DATETIME NOT NULL, hostname_tested VARCHAR(255) NOT NULL, ip_tested VARCHAR(255) NOT NULL, fail_reason VARCHAR(1024) DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_1056221A18F45C82 (website_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE uptime_result (id INT AUTO_INCREMENT NOT NULL, website_id INT NOT NULL, http_status INT DEFAULT NULL, load_time_in_ms NUMERIC(10, 7) DEFAULT NULL, error_message VARCHAR(1024) DEFAULT NULL, INDEX IDX_ADFE37E818F45C82 (website_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE website (id INT AUTO_INCREMENT NOT NULL, domain VARCHAR(255) NOT NULL, ip VARCHAR(255) DEFAULT NULL, port INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scheduled_command (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, command VARCHAR(100) NOT NULL, arguments VARCHAR(250) DEFAULT NULL, cron_expression VARCHAR(100) DEFAULT NULL, last_execution DATETIME NOT NULL, last_return_code INT DEFAULT NULL, log_file VARCHAR(100) DEFAULT NULL, priority INT NOT NULL, execute_immediately TINYINT(1) NOT NULL, disabled TINYINT(1) NOT NULL, locked TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tls_scan_result ADD CONSTRAINT FK_1056221A18F45C82 FOREIGN KEY (website_id) REFERENCES website (id)');
        $this->addSql('ALTER TABLE uptime_result ADD CONSTRAINT FK_ADFE37E818F45C82 FOREIGN KEY (website_id) REFERENCES website (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tls_scan_result DROP FOREIGN KEY FK_1056221A18F45C82');
        $this->addSql('ALTER TABLE uptime_result DROP FOREIGN KEY FK_ADFE37E818F45C82');
        $this->addSql('DROP TABLE tls_scan_result');
        $this->addSql('DROP TABLE uptime_result');
        $this->addSql('DROP TABLE website');
        $this->addSql('DROP TABLE scheduled_command');
    }
}
