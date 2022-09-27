<?php declare(strict_types=1);

namespace Wexo\ProductLabels\ScheduledTask;

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

class UpdateProductLabelsTask extends ScheduledTask
{
    public static function getTaskName(): string
    {
        return 'set2022.update_product_labels_task';
    }

    public static function getDefaultInterval(): int
    {
        return 3600; // 1 hour
    }
}
