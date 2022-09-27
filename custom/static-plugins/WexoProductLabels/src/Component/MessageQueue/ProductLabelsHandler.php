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
    /**
     * @param EntityRepository $productRepository
     * @param EntityRepository $logEntryRepository
     * @param SyncServiceInterface $syncService
     */
    public function __construct(
        protected EntityRepository $productRepository,
        protected EntityRepository $logEntryRepository,
        protected SyncServiceInterface $syncService
    ) { }

    /**
     * @param ProductLabelsProductEntity $message
     */
    public function handle($message): void
    {
        try {
            // We replace any existing labels with the current ones to ensure label switches are made
            $body = [
                'id'           => $message->getProductId(),
                'customFields' => [
                    WexoProductLabels::CUSTOM_LABELS_NAME => $message->getCustomLabels()
                ]
            ];

            $behavior = new SyncBehavior(
                true,
                false,
                'disable-indexing'
            );

            $payload = [
                'write-product' => [
                    'entity' => 'product',
                    'action' => 'upsert',
                    'payload' => [$body]
                ]
            ];

            $operations = [];
            foreach ($payload as $key => $operation) {
                $operations[] = new SyncOperation(
                    (string) $key,
                    $operation['entity'],
                    $operation['action'],
                    $operation['payload'],
                    3
                );
            }

            $context = Context::createDefaultContext();
            $context->scope(
                Context::CRUD_API_SCOPE,
                function (Context $context) use ($operations, $behavior): SyncResult {
                    return $this->syncService->sync($operations, $context, $behavior);
                }
            );
        } catch (\Error | \TypeError | \Exception $e) {
            $this->logEntryRepository->create(
                [
                    [
                        'message'   => WexoProductLabels::PRODUCT_LABEL_UPDATE_ERROR,
                        'context'   => [
                            'id' => $message->getProductId() ?? null,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                            'errorType' => get_class($e)
                        ],
                        'level'     => Logger::ERROR,
                        'channel'   => WexoProductLabels::LOG_CHANNEL
                    ]
                ],
                $context
            );
        }
    }

    /**
     * @return iterable
     */
    public static function getHandledMessages(): iterable
    {
        return [ProductLabelsProductEntity::class];
    }
}
