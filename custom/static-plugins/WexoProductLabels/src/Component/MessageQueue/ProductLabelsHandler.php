<?php declare(strict_types=1);

namespace Wexo\ProductLabels\Component\MessageQueue;

use Monolog\Logger;
use Shopware\Core\Framework\Api\Sync\SyncBehavior;
use Shopware\Core\Framework\Api\Sync\SyncOperation;
use Shopware\Core\Framework\Api\Sync\SyncResult;
use Shopware\Core\Framework\Api\Sync\SyncServiceInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\MessageQueue\Handler\AbstractMessageHandler;
use Wexo\ProductLabels\Component\ProductLabelsProductEntity;
use Wexo\ProductLabels\WexoProductLabels;

class ProductLabelsHandler extends AbstractMessageHandler
{

}
