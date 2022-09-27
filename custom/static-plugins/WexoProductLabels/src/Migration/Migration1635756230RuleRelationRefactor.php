<?php declare(strict_types=1);

namespace Wexo\ProductLabels\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1635756230RuleRelationRefactor extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1635756230;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement(
            'ALTER TABLE `label_rule_relation`
                        DROP COLUMN `id`,
                        DROP COLUMN `created_at`,
                        DROP COLUMN `updated_at`;
                    '
        );

        $connection->executeStatement(
            'DELETE FROM `label_rule_relation` WHERE label_id NOT IN (SELECT ID FROM `custom_labels`);'
        );
        $connection->executeStatement(
            'DELETE FROM `label_rule_relation` WHERE rule_id NOT IN (SELECT ID FROM `product_stream`);'
        );

        $connection->executeStatement(
            'ALTER TABLE `label_rule_relation` RENAME COLUMN rule_id TO product_stream_id;'
        );

        $connection->executeStatement(
            'ALTER TABLE `label_rule_relation`
                    ADD CONSTRAINT `fk.label_rule_relation.label_id` FOREIGN KEY (`label_id`)
                        REFERENCES `custom_labels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;'
        );

        $connection->executeStatement(
            'ALTER TABLE `label_rule_relation`
                    ADD CONSTRAINT `fk.label_rule_relation.product_stream_id` FOREIGN KEY (`product_stream_id`)
                        REFERENCES `product_stream` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;'
        );
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
