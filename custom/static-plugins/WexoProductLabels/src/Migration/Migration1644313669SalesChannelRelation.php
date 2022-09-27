<?php declare(strict_types=1);

namespace Wexo\ProductLabels\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1644313669SalesChannelRelation extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1644313669;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            ALTER TABLE `custom_labels`
            ADD `sales_channel_ids` JSON NULL AFTER `custom_classes`
            ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
