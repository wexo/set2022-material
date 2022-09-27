<?php declare(strict_types=1);

namespace Wexo\ProductLabels\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1570025268cusomt_labels extends MigrationStep
{
    /**
     * @return int
     */
    public function getCreationTimestamp(): int
    {
        return 1570025268;
    }

    /**
     * @param Connection $connection
     */
    public function update(Connection $connection): void
    {
        $connection->executeStatement('
        Create TABLE IF NOT EXISTS `custom_labels`(
            `id` BINARY(16) NOT NULL,
            `active` BOOLEAN NOT NULL DEFAULT 1,
            `type` VARCHAR(255) NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `position` VARCHAR(255) NOT NULL,
            `shape` VARCHAR(255) NULL,
            `color` VARCHAR(255) NULL,
            `content` VARCHAR(255) NULL,
            `img_url` VARCHAR(255) NULL,
            `custom_classes` VARCHAR(255) NULL,
            `created_at` DATETIME(3) NOT NULL,
            `updated_at` DATETIME(3) NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
     ');
    }

    /**
     * @param Connection $connection
     */
    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
