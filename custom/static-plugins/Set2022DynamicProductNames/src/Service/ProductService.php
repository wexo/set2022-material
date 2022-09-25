<?php declare(strict_types=1);

namespace Set2022DynamicProductNames\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Symfony\Component\Messenger\MessageBusInterface;
use Set2022DynamicProductNames\Component\ProductPrefixEntity;

class ProductService
{
    /**
     * @param EntityRepository $productRepository
     * @param MessageBusInterface $messageBus
     */
    public function __construct(
        protected EntityRepository $productRepository,
        protected MessageBusInterface $messageBus
    ) {
    }

    /**
     * @param string $action
     * @param array|null $productNumbers
     * @param bool $force
     * @return bool
     */
    public function updateProductPrefixData(
        string $action,
        array|null $productNumbers,
        bool $force
    ): bool {
        $criteria = new Criteria();

        if ($productNumbers) {
            $criteria->addFilter(new EqualsAnyFilter('productNumber', $productNumbers));
        }

        $productIds = $this->productRepository->searchIds($criteria, Context::createDefaultContext())->getIds();

        foreach ($productIds as $productId) {
            $productPrefix = new ProductPrefixEntity($productId, $action);
            $productPrefix->setForce($force);

            $this->messageBus->dispatch($productPrefix);
        }

        return true;
    }
}
