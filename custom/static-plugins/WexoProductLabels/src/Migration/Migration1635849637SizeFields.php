<?php declare(strict_types=1);

namespace Wexo\ProductLabels\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1635849637SizeFields extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1635849637;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement(
            'ALTER TABLE `custom_labels`
                    ADD COLUMN `height` varchar(10) NULL AFTER `margin_left`,
                    ADD COLUMN `width` varchar(10) NULL AFTER `height`;'
        );
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
