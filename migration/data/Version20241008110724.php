<?php

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241008110724 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $platform = $this->connection->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');

        $this->addSql("SET @@session.sql_mode = ''");

        $voucherSeriesTable = $schema->getTable('oxvoucherseries');

        //Setup
        if ($voucherSeriesTable->hasColumn('OXDISCOUNTTYPE')) {
            $this->addSql(
                "ALTER TABLE oxvoucherseries CHANGE `OXDISCOUNTTYPE` `OXDISCOUNTTYPE` enum('percent','absolute','shipfree') NOT NULL DEFAULT 'absolute' COMMENT 'Discount type (percent, absolute, shipfree)'"
            );
        }
    }

    public function down(Schema $schema): void
    {
    }
}
