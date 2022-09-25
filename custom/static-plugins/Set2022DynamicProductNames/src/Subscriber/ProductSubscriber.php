<?php declare(strict_types=1);

namespace Set2022DynamicProductNames\Subscriber;

use Set2022DynamicProductNames\Set2022DynamicProductNames;
use Shopware\Core\Content\Product\Events\ProductListingResultEvent;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Content\Product\ProductEvents;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductSubscriber implements EventSubscriberInterface
{
    /**
     * @param EntityRepository $productRepository
     * @param EntityRepository $categoryRepository
     * @param SystemConfigService $systemConfigService
     */
    public function __construct(
        public EntityRepository $productRepository,
        public EntityRepository $categoryRepository,
        public SystemConfigService $systemConfigService
    ) {
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            ProductEvents::PRODUCT_LOADED_EVENT => 'onProductLoaded',
            ProductListingResultEvent::class => 'onProductListingResult',
            ProductPageLoadedEvent::class => 'onProductPageLoaded'
        ];
    }

    public function onProductLoaded(EntityLoadedEvent $event): void
    {
        // Issues: Administration. Product repository search. etc.
        $x = 1;
    }

    /**
     * @param ProductListingResultEvent $event
     * @return void
     */
    public function onProductListingResult(ProductListingResultEvent $event): void
    {
        $products = $event->getResult()->getEntities();
        /** @var ProductEntity $product */
        foreach ($products as $product) {
            $prefix = $product->getCustomFields()[Set2022DynamicProductNames::CUSTOM_FIELD_NAME] ?? null;
        }
        // TODO: Implement same logic as on PDP
    }

    /**
     * @param ProductPageLoadedEvent $event
     * @return void
     */
    public function onProductPageLoaded(ProductPageLoadedEvent $event): void
    {
        $prefix = $this->systemConfigService->get('Set2022DynamicProductNames.config.prefix') ?? null;

        $product = $event->getPage()->getProduct();
        $product->addArrayExtension('productPrefix', ['prefix' => $prefix]);

        return;

        // Get categories from Category IDs

        $categoryIds = $product->getCategoryIds();
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsAnyFilter('id', $categoryIds));
        // OR
        $criteria = new Criteria([$categoryIds]);

        $categories = $this->categoryRepository->search($criteria, $event->getContext());

        // Example: Get categories from product with associations
        $criteria = new Criteria([$product->getId()]);
        // Try with or without association
        //$criteria->addAssociation('categories');
        /** @var ProductEntity $product */
        $product = $this->productRepository->search($criteria, $event->getContext())->first();
        $categories = $product->getCategories();
        foreach ($categories as $category) {
            // TODO: Category data logic
        }
    }
}
