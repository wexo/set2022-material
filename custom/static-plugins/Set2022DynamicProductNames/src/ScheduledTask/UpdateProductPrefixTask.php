<?php declare(strict_types=1);

namespace Set2022DynamicProductNames\ScheduledTask;

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

class UpdateProductPrefixTask extends ScheduledTask
{
    public static function getTaskName(): string
    {
        return 'set2022.update_product_prefix_task';
    }

    public static function getDefaultInterval(): int
    {
        return 300; // 5 minutes
    }
}
