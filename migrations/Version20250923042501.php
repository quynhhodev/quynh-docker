<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250923042501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE hdb_catalog.hdb_scheduled_event_invocation_logs DROP CONSTRAINT hdb_scheduled_event_invocation_logs_event_id_fkey');
        $this->addSql('ALTER TABLE hdb_catalog.hdb_cron_event_invocation_logs DROP CONSTRAINT hdb_cron_event_invocation_logs_event_id_fkey');
        $this->addSql('DROP TABLE hdb_catalog.hdb_schema_notifications');
        $this->addSql('DROP TABLE hdb_catalog.hdb_cron_events');
        $this->addSql('DROP TABLE hdb_catalog.hdb_metadata');
        $this->addSql('DROP TABLE hdb_catalog.hdb_scheduled_event_invocation_logs');
        $this->addSql('DROP TABLE hdb_catalog.hdb_scheduled_events');
        $this->addSql('DROP TABLE hdb_catalog.hdb_version');
        $this->addSql('DROP TABLE hdb_catalog.hdb_action_log');
        $this->addSql('DROP TABLE hdb_catalog.hdb_cron_event_invocation_logs');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SCHEMA hdb_catalog');
        $this->addSql('CREATE TABLE hdb_catalog.hdb_schema_notifications (id INT NOT NULL, notification JSON NOT NULL, resource_version INT DEFAULT 1 NOT NULL, instance_id UUID NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT \'now()\', PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE hdb_catalog.hdb_cron_events (id TEXT DEFAULT \'hdb_catalog.gen_hasura_uuid()\' NOT NULL, trigger_name TEXT NOT NULL, scheduled_time TIMESTAMP(0) WITH TIME ZONE NOT NULL, status TEXT DEFAULT \'scheduled\' NOT NULL, tries INT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT \'now()\', next_retry_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX hdb_cron_events_unique_scheduled ON hdb_catalog.hdb_cron_events (trigger_name, scheduled_time) WHERE (status = \'scheduled\'::text)');
        $this->addSql('CREATE INDEX hdb_cron_event_status ON hdb_catalog.hdb_cron_events (status)');
        $this->addSql('CREATE TABLE hdb_catalog.hdb_metadata (id INT NOT NULL, metadata JSON NOT NULL, resource_version INT DEFAULT 1 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX hdb_metadata_resource_version_key ON hdb_catalog.hdb_metadata (resource_version)');
        $this->addSql('CREATE TABLE hdb_catalog.hdb_scheduled_event_invocation_logs (id TEXT DEFAULT \'hdb_catalog.gen_hasura_uuid()\' NOT NULL, event_id TEXT DEFAULT NULL, status INT DEFAULT NULL, request JSON DEFAULT NULL, response JSON DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT \'now()\', PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E275F59A71F7E88B ON hdb_catalog.hdb_scheduled_event_invocation_logs (event_id)');
        $this->addSql('CREATE TABLE hdb_catalog.hdb_scheduled_events (id TEXT DEFAULT \'hdb_catalog.gen_hasura_uuid()\' NOT NULL, webhook_conf JSON NOT NULL, scheduled_time TIMESTAMP(0) WITH TIME ZONE NOT NULL, retry_conf JSON DEFAULT NULL, payload JSON DEFAULT NULL, header_conf JSON DEFAULT NULL, status TEXT DEFAULT \'scheduled\' NOT NULL, tries INT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT \'now()\', next_retry_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, comment TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX hdb_scheduled_event_status ON hdb_catalog.hdb_scheduled_events (status)');
        $this->addSql('CREATE TABLE hdb_catalog.hdb_version (hasura_uuid UUID DEFAULT \'hdb_catalog.gen_hasura_uuid()\' NOT NULL, version TEXT NOT NULL, upgraded_on TIMESTAMP(0) WITH TIME ZONE NOT NULL, cli_state JSONB DEFAULT \'{}\' NOT NULL, console_state JSONB DEFAULT \'{}\' NOT NULL, PRIMARY KEY(hasura_uuid))');
        $this->addSql('CREATE TABLE hdb_catalog.hdb_action_log (id UUID DEFAULT \'hdb_catalog.gen_hasura_uuid()\' NOT NULL, action_name TEXT DEFAULT NULL, input_payload JSONB NOT NULL, request_headers JSONB NOT NULL, session_variables JSONB NOT NULL, response_payload JSONB DEFAULT NULL, errors JSONB DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT \'now()\' NOT NULL, response_received_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, status TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE hdb_catalog.hdb_cron_event_invocation_logs (id TEXT DEFAULT \'hdb_catalog.gen_hasura_uuid()\' NOT NULL, event_id TEXT DEFAULT NULL, status INT DEFAULT NULL, request JSON DEFAULT NULL, response JSON DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT \'now()\', PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX hdb_cron_event_invocation_event_id ON hdb_catalog.hdb_cron_event_invocation_logs (event_id)');
        $this->addSql('ALTER TABLE hdb_catalog.hdb_scheduled_event_invocation_logs ADD CONSTRAINT hdb_scheduled_event_invocation_logs_event_id_fkey FOREIGN KEY (event_id) REFERENCES hdb_catalog.hdb_scheduled_events (id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hdb_catalog.hdb_cron_event_invocation_logs ADD CONSTRAINT hdb_cron_event_invocation_logs_event_id_fkey FOREIGN KEY (event_id) REFERENCES hdb_catalog.hdb_cron_events (id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
