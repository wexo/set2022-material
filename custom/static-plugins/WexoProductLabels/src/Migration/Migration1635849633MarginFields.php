<?php declare(strict_types=1);

namespace Wexo\ProductLabels\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1635849633MarginFields extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1635849633;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement(
            'ALTER TABLE `custom_labels`
                    ADD COLUMN `margin_top` varchar(10) NULL AFTER `media_id`,
                    ADD COLUMN `margin_right` varchar(10) NULL AFTER `margin_top`,
                    ADD COLUMN `margin_bottom` varchar(10) NULL AFTER `margin_right`,
                    ADD COLUMN `margin_left` varchar(10) NULL AFTER `margin_bottom`;'
        );
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
