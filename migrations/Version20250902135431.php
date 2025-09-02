<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250902135431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE obs_base');
        $this->addSql('DROP TABLE ort1');
        $this->addSql('DROP TABLE quellenthema');
        $this->addSql('DROP TABLE thema');
        $this->addSql('DROP TABLE thema_in');
        $this->addSql('ALTER TABLE data_entry ALTER id TYPE INT');
        $this->addSql('ALTER TABLE data_entry ALTER topic_id TYPE INT');
        $this->addSql('ALTER TABLE data_entry ALTER location_id TYPE INT');
        $this->addSql('ALTER TABLE data_entry ADD CONSTRAINT FK_659E51EF64D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE');
        $this->addSql('ALTER INDEX idx_5476042_idx_659e51ef1f55203d RENAME TO IDX_659E51EF1F55203D');
        $this->addSql('ALTER INDEX idx_5476042_idx_659e51ef64d218e RENAME TO IDX_659E51EF64D218E');
        $this->addSql('ALTER TABLE location ALTER id TYPE INT');
        $this->addSql('ALTER TABLE location ALTER parent_id TYPE INT');
        $this->addSql('ALTER TABLE location ALTER type_id TYPE INT');
        $this->addSql('ALTER INDEX idx_5476045_idx_5e9e89cb727aca70 RENAME TO IDX_5E9E89CB727ACA70');
        $this->addSql('ALTER INDEX idx_5476045_idx_5e9e89cbc54c8c93 RENAME TO IDX_5E9E89CBC54C8C93');
        $this->addSql('ALTER TABLE topic ALTER id TYPE INT');
        $this->addSql('ALTER TABLE topic ALTER type_id TYPE INT');
        $this->addSql('ALTER TABLE topic ALTER parent_id TYPE INT');
        $this->addSql('ALTER INDEX idx_5476065_idx_9d40de1bc54c8c93 RENAME TO IDX_9D40DE1BC54C8C93');
        $this->addSql('ALTER INDEX idx_5476065_idx_9d40de1b727aca70 RENAME TO IDX_9D40DE1B727ACA70');
        $this->addSql('ALTER TABLE type ALTER id TYPE INT');
        $this->addSql('ALTER TABLE type ALTER id DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA bernhist');
        $this->addSql('CREATE TABLE obs_base (start SMALLINT DEFAULT NULL, "end" SMALLINT DEFAULT NULL, std_sys_key SMALLINT DEFAULT NULL, src_term_key SMALLINT DEFAULT NULL, rohdata_source_key SMALLINT DEFAULT NULL, obs_key BIGINT DEFAULT NULL, flag_note SMALLINT DEFAULT NULL, rohdata_zeile SMALLINT DEFAULT NULL, rohdata_spalte SMALLINT DEFAULT NULL, obs_value DOUBLE PRECISION DEFAULT NULL)');
        $this->addSql('CREATE TABLE ort1 (key DOUBLE PRECISION DEFAULT NULL, name VARCHAR(150) DEFAULT NULL, type VARCHAR(150) DEFAULT NULL, level1 DOUBLE PRECISION DEFAULT NULL, level2 VARCHAR(150) DEFAULT NULL, level3 VARCHAR(150) DEFAULT NULL, level4 VARCHAR(150) DEFAULT NULL, level5 VARCHAR(150) DEFAULT NULL)');
        $this->addSql('CREATE TABLE quellenthema (src_term_key SMALLINT DEFAULT NULL, std_term_key SMALLINT DEFAULT NULL, std_term_name VARCHAR(150) DEFAULT NULL, src_term_type VARCHAR(150) DEFAULT NULL, flag_note1 SMALLINT DEFAULT NULL)');
        $this->addSql('CREATE TABLE thema (std_term_name VARCHAR(150) DEFAULT NULL, std_term_type VARCHAR(150) DEFAULT NULL, std_term_thesa_level SMALLINT DEFAULT NULL, std_term_thesa_ord SMALLINT DEFAULT NULL, std_term_formel VARCHAR(150) DEFAULT NULL, n_elem SMALLINT DEFAULT NULL, flag_note SMALLINT DEFAULT NULL, std_term_key SMALLINT DEFAULT NULL)');
        $this->addSql('CREATE TABLE thema_in (std_term_key SMALLINT DEFAULT NULL, std_term_in SMALLINT DEFAULT NULL)');
        $this->addSql('ALTER TABLE data_entry DROP CONSTRAINT FK_659E51EF64D218E');
        $this->addSql('ALTER TABLE data_entry ALTER id TYPE BIGINT');
        $this->addSql('ALTER TABLE data_entry ALTER topic_id TYPE BIGINT');
        $this->addSql('ALTER TABLE data_entry ALTER location_id TYPE BIGINT');
        $this->addSql('ALTER INDEX idx_659e51ef1f55203d RENAME TO idx_5476042_idx_659e51ef1f55203d');
        $this->addSql('ALTER INDEX idx_659e51ef64d218e RENAME TO idx_5476042_idx_659e51ef64d218e');
        $this->addSql('ALTER TABLE location ALTER id TYPE BIGINT');
        $this->addSql('ALTER TABLE location ALTER parent_id TYPE BIGINT');
        $this->addSql('ALTER TABLE location ALTER type_id TYPE BIGINT');
        $this->addSql('ALTER INDEX idx_5e9e89cbc54c8c93 RENAME TO idx_5476045_idx_5e9e89cbc54c8c93');
        $this->addSql('ALTER INDEX idx_5e9e89cb727aca70 RENAME TO idx_5476045_idx_5e9e89cb727aca70');
        $this->addSql('ALTER TABLE topic ALTER id TYPE BIGINT');
        $this->addSql('ALTER TABLE topic ALTER type_id TYPE BIGINT');
        $this->addSql('ALTER TABLE topic ALTER parent_id TYPE BIGINT');
        $this->addSql('ALTER INDEX idx_9d40de1bc54c8c93 RENAME TO idx_5476065_idx_9d40de1bc54c8c93');
        $this->addSql('ALTER INDEX idx_9d40de1b727aca70 RENAME TO idx_5476065_idx_9d40de1b727aca70');
        $this->addSql('ALTER TABLE type ALTER id TYPE BIGINT');
        $this->addSql('ALTER TABLE type ALTER id SET DEFAULT nextval(\'type_id_seq\'::regclass)');
    }
}
