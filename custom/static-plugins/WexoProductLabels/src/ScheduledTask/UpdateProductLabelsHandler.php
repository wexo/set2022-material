<?php declare(strict_types=1);

namespace Wexo\ProductLabels\ScheduledTask;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;
use Wexo\ProductLabels\Service\ProductLabelsService;

class UpdateProductLabelsHandler extends ScheduledTaskHandler
{
    /**
     * @param EntityRepository $scheduledTaskRepository
     * @param ProductLabelsService $productLabelsService
     */
    public function __construct(
        protected EntityRepository $scheduledTaskRepository,
        protected ProductLabelsService $productLabelsService
    ) {
        parent::__construct($scheduledTaskRepository);
    }

    /**
     * @return iterable
     */
    public static function getHandledMessages(): iterable
    {
        return [UpdateProductLabelsTask::class];
    }

    public function run(): void
    {
        try {
            $this->productLabelsService->updateLabels(Context::createDefaultContext());
        } catch (\Error | \TypeError | \Exception $exception) {
            // TODO: Error handling
        }
    }
}
