<?php declare(strict_types=1);

namespace Wexo\ProductLabels\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1635757867LabelMediaEntity extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1635757867;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement(
            'ALTER TABLE `custom_labels`
                    ADD COLUMN `media_id` BINARY(16) NULL AFTER `img_url`,
                    ADD CONSTRAINT `fk.custom_labels.media_id` FOREIGN KEY (`media_id`)
                        REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;'
        );
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
