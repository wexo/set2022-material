<?php declare(strict_types=1);

namespace Wexo\ProductLabels\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1646119820RemovePrimaryKeyConstraint extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1646119820;
    }

    public function update(Connection $connection): void
    {
        $result = $connection
            ->executeQuery("
            SHOW KEYS FROM `label_rule_relation`
            WHERE Key_name = 'PRIMARY'
            AND Column_name = 'product_stream_id'
            ")
            ->fetchAllAssociative();
        if ($result) {
            $connection->executeStatement('
                ALTER TABLE `label_rule_relation`
                DROP FOREIGN KEY `fk.label_rule_relation.product_stream_id`,
                DROP PRIMARY KEY
            ');
            $connection->executeStatement('
                 ALTER TABLE `label_rule_relation`
                 ADD CONSTRAINT `fk.label_rule_relation.product_stream_id`
                    FOREIGN KEY (`product_stream_id`)
                    REFERENCES `product_stream` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
            ');
        }
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
