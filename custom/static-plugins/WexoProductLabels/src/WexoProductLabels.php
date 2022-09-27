<?php declare(strict_types=1);

namespace Wexo\ProductLabels;

use Shopware\Core\Framework\Plugin;

class WexoProductLabels extends Plugin
{
    public const LOG_CHANNEL = 'product-labels';
    public const PRODUCT_LABEL_UPDATE_SUCCESS = 'product-labels.update.success';
    public const PRODUCT_LABEL_UPDATE_ERROR = 'product-labels.update.error';
    public const CUSTOM_LABELS_NAME = 'customLabels';
}
