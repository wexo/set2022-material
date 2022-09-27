<?php declare(strict_types=1);

namespace Wexo\ProductLabels\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1596438247label_rule_relation extends MigrationStep
{
    /**
     * @return int
     */
    public function getCreationTimestamp(): int
    {
        return 1596438247;
    }

    /**
     * @param Connection $connection
     * @throws \Exception
     */
    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `label_rule_relation` (
              `id` BINARY(16) NOT NULL,
              `label_id` BINARY(16) NOT NULL,
              `rule_id` BINARY(16) NOT NULL,
              `created_at` DATETIME(3) NOT NULL,
              `updated_at` DATETIME(3) NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeStatement('
            ALTER TABLE `custom_labels` CHANGE `position` `position` VARCHAR(255) CHARACTER SET utf8mb4
            COLLATE utf8mb4_unicode_ci NOT NULL;
        ');

        $result = $connection
            ->executeQuery("SHOW COLUMNS FROM `custom_labels` LIKE 'rule_id'")
            ->fetchAllAssociative();
        if ($result) {
            $labels = $connection
                ->executeQuery('SELECT HEX(id) as label_id, rule_id FROM custom_labels')
                ->fetchAllAssociative();
            foreach ($labels as $label) {
                $id = Uuid::randomHex();
                $connection->executeStatement('
                    INSERT INTO `label_rule_relation` (`id`, `label_id`, `rule_id`, `created_at`, `updated_at`)
                    VALUES (
                        UNHEX("' . $id . '"),
                        UNHEX("' . $label['label_id'] . '"),
                        UNHEX("' . $label['rule_id'] . '"),
                        "' . (new \DateTime)->format('Y-m-d H:i:s') . '",
                        "' . (new \DateTime)->format('Y-m-d H:i:s') . '"
                    )
                ');
            }

            $connection->executeStatement(
                "ALTER TABLE `custom_labels` DROP `rule_id`"
            );
        }
    }

    /**
     * @param Connection $connection
     */
    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
