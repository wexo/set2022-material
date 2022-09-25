<?php declare(strict_types=1);

namespace Set2022DynamicProductNames\Component\MessageQueue;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\Api\Sync\SyncBehavior;
use Shopware\Core\Framework\Api\Sync\SyncOperation;
use Shopware\Core\Framework\Api\Sync\SyncResult;
use Shopware\Core\Framework\Api\Sync\SyncServiceInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexerRegistry;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\MessageQueue\Handler\AbstractMessageHandler;
use Shopware\Core\System\Locale\LocaleEntity;
use Symfony\Component\Stopwatch\Stopwatch;
use Set2022DynamicProductNames\Component\ProductPrefixEntity;

class ProductPrefixHandler extends AbstractMessageHandler
{
    /**
     * @param EntityRepository $productRepository
     * @param EntityRepository $logEntryRepository
     * @param EntityRepository $localeRepository
     * @param EntityRepository $languageRepository
     * @param SyncServiceInterface $syncService
     */
    public function __construct(
        protected EntityRepository $productRepository,
        protected EntityRepository $logEntryRepository,
        protected EntityRepository $localeRepository,
        protected EntityRepository $languageRepository,
        protected SyncServiceInterface $syncService
    ) {
    }

    /**
     * @param ProductPrefixEntity $message
     */
    public function handle($message): void
    {
        $context = Context::createDefaultContext();

        $stopwatch = new Stopwatch();
        $localeEvent = $stopwatch->start('locale');

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('code', 'en-GB'));
        $criteria->addAssociation('languages');
        /** @var LocaleEntity $english */
        $english = $this->localeRepository->search($criteria, $context)->first();

        $englishId = null;
        foreach ($english->getLanguages() as $language) {
            if (!$englishId) {
                $englishId = $language->getId();
                break;
            }
        }

        $localeEvent->stop();
        $time1 = $localeEvent->getEndTime() - $localeEvent->getStartTime();

        $languageEvent = $stopwatch->start('language');
        $criteria = new Criteria();
        $criteria->addAssociation('locale');
        $criteria->addFilter(new EqualsFilter('locale.code', 'en-GB'));

        $language = $this->languageRepository->searchIds($criteria, $context)->firstId();

        $languageEvent->stop();
        $time2 = $languageEvent->getEndTime() - $languageEvent->getStartTime();


        $englishKey = $message->getProductId() . '-' . $englishId;

        $criteria = new Criteria([$message->getProductId()]);
        $criteria->addAssociation('translations');

        /** @var ProductEntity $product */
        $product = $this->productRepository->search(
            $criteria,
            $context
        )->first();

        $translations = $product->getTranslations()->getElements() ?? null;
        $englishName = $translations[$englishKey]->getName() ?? null;

        // TODO: Add / remove logic
        $currentPrefix = $product->getCustomFields()['customPrefix'] ?? null;

        $body = [
            'id' => $message->getProductId(),
            'customFields' => [
                'customPrefix' => $englishName . ' / '
            ]
        ];

        $behavior = new SyncBehavior(
            true,
            false,
            EntityIndexerRegistry::DISABLE_INDEXING
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
                $key,
                $operation['entity'],
                $operation['action'],
                $operation['payload'],
                3
            );
        }

        $context->scope(
            Context::CRUD_API_SCOPE,
            function (Context $context) use ($operations, $behavior): SyncResult {
                return $this->syncService->sync($operations, $context, $behavior);
            }
        );
    }

    /**
     * @return iterable
     */
    public static function getHandledMessages(): iterable
    {
        return [ProductPrefixEntity::class];
    }
}
