<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20210222210653 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE address_book (id INTEGER NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, house_no VARCHAR(255) NOT NULL, zip VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, birth_date DATE NOT NULL, email VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B6A973DA444F97DD ON address_book (phone)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B6A973DAE7927C74 ON address_book (email)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE address_book');
    }
}
