<?php

declare(strict_types=1);

namespace Nasumilu\CGS4183\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220326234445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id SERIAL NOT NULL, customer INT NOT NULL, street VARCHAR(128) NOT NULL, city VARCHAR(32) NOT NULL, region VARCHAR(32) NOT NULL, country VARCHAR(32) NOT NULL, postal_code VARCHAR(16) NOT NULL, address_type VARCHAR(8) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D4E6F8181398E09 ON address (customer)');
        $this->addSql('CREATE TABLE category (id SERIAL NOT NULL, name VARCHAR(32) NOT NULL, description TEXT DEFAULT NULL, image_path VARCHAR(128) NOT NULL, image_mime_type VARCHAR(16) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C15E237E06 ON category (name)');
        $this->addSql('CREATE TABLE customer (id SERIAL NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE invoice (id VARCHAR(32) NOT NULL, customer INT NOT NULL, invoice_date DATE NOT NULL, billing_street VARCHAR(128) NOT NULL, billing_city VARCHAR(32) NOT NULL, billing_region VARCHAR(32) NOT NULL, billing_country VARCHAR(32) NOT NULL, billing_postal_code VARCHAR(16) NOT NULL, shipping_street VARCHAR(128) NOT NULL, shipping_city VARCHAR(32) NOT NULL, shipping_region VARCHAR(32) NOT NULL, shipping_country VARCHAR(32) NOT NULL, shipping_postal_code VARCHAR(16) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9065174481398E09 ON invoice (customer)');
        $this->addSql('CREATE TABLE invoice_item (id SERIAL NOT NULL, invoice VARCHAR(32) NOT NULL, product VARCHAR(32) NOT NULL, description TEXT DEFAULT NULL, quantity INT NOT NULL, unit_amount INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1DDE477B90651744 ON invoice_item (invoice)');
        $this->addSql('CREATE TABLE product (id SERIAL NOT NULL, category INT NOT NULL, amount INT NOT NULL, name VARCHAR(32) NOT NULL, description TEXT DEFAULT NULL, image_path VARCHAR(128) NOT NULL, image_mime_type VARCHAR(16) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD5E237E06 ON product (name)');
        $this->addSql('CREATE INDEX IDX_D34A04AD64C19C1 ON product (category)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8181398E09 FOREIGN KEY (customer) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_9065174481398E09 FOREIGN KEY (customer) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_item ADD CONSTRAINT FK_1DDE477B90651744 FOREIGN KEY (invoice) REFERENCES invoice (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD64C19C1 FOREIGN KEY (category) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04AD64C19C1');
        $this->addSql('ALTER TABLE address DROP CONSTRAINT FK_D4E6F8181398E09');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_9065174481398E09');
        $this->addSql('ALTER TABLE invoice_item DROP CONSTRAINT FK_1DDE477B90651744');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE invoice_item');
        $this->addSql('DROP TABLE product');
    }
}
