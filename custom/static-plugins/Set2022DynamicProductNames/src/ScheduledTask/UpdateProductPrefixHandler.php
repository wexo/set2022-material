<?php declare(strict_types=1);

namespace Set2022DynamicProductNames\ScheduledTask;

use Set2022DynamicProductNames\Set2022DynamicProductNames;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;
use Set2022DynamicProductNames\Service\ProductService;

class UpdateProductPrefixHandler extends ScheduledTaskHandler
{
    /**
     * @param EntityRepository $scheduledTaskRepository
     * @param ProductService $productService
     */
    public function __construct(
        EntityRepository $scheduledTaskRepository,
        protected ProductService $productService
    ) {
        parent::__construct($scheduledTaskRepository);
    }

    /**
     * @return iterable
     */
    public static function getHandledMessages(): iterable
    {
        return [UpdateProductPrefixTask::class];
    }

    public function run(): void
    {
        try {
            $result = $this->productService->updateProductPrefixData(
                Set2022DynamicProductNames::ACTION_ADD,
                null,
                false
            );
            if (!$result) {
                // TODO: Log error
            }
        } catch (\Error | \TypeError | \Exception $exception) {
            // TODO: Error handling
        }
    }
}
