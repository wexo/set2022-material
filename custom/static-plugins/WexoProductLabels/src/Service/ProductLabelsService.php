<?php declare(strict_types=1);

namespace Wexo\ProductLabels\Service;

use Monolog\Logger;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Content\ProductStream\ProductStreamEntity;
use Shopware\Core\Content\ProductStream\Service\ProductStreamBuilder;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\SearchRequestException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Parser\QueryStringParser;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Wexo\ProductLabels\Component\ProductLabelsProductEntity;
use Wexo\ProductLabels\Core\Content\Label\LabelEntity;
use Wexo\ProductLabels\WexoProductLabels;

class ProductLabelsService
{
    /**
     * ProductLabelsService constructor.
     * @param EntityRepository $productRepository
     * @param EntityRepository $customLabelsRepository
     * @param EntityRepository $labelRuleRelationRepository
     * @param EntityRepository $productStreamRepository
     * @param EntityRepository $logEntryRepository
     * @param ProductStreamBuilder $productStreamBuilder
     * @param MessageBusInterface $bus
     * @param DefinitionInstanceRegistry $definitionInstanceRegistry
     */
    public function __construct(
        protected EntityRepository $productRepository,
        protected EntityRepository $customLabelsRepository,
        protected EntityRepository $labelRuleRelationRepository,
        protected EntityRepository $productStreamRepository,
        protected EntityRepository $logEntryRepository,
        protected ProductStreamBuilder $productStreamBuilder,
        protected MessageBusInterface $bus,
        protected DefinitionInstanceRegistry $definitionInstanceRegistry
    ) { }

    public function updateLabels(Context $context): void
    {
        $context->setConsiderInheritance(true);

        // All customLabel rules
        $customLabels = $this->getCustomLabelRules($context) ?? [];

        // All products that currently have a custom label set in customFields
        $currentProductCustomLabels = $this->getCurrentProductsLabels($context) ?? [];

        // All products that should have labels assigned
        $productsToUpdate = $this->getCustomLabelProducts($customLabels, $context) ?? [];

        // Looking for all products where labels no longer are set and sends an empty customLabel to message queue
        foreach ($currentProductCustomLabels as $currentProductCustomLabelId) {
            try {
                if (!isset($productsToUpdate[$currentProductCustomLabelId])) {
                    $removeLabelFromProduct = new ProductLabelsProductEntity(
                        $currentProductCustomLabelId,
                        []
                    );

                    $this->bus->dispatch(new Envelope($removeLabelFromProduct));
                }
            } catch (\Error | \TypeError | \Exception $e) {
                $this->logEntryRepository->create(
                    [
                        [
                            'message'   => WexoProductLabels::PRODUCT_LABEL_UPDATE_ERROR,
                            'context'   => [
                                'id' => $currentProductCustomLabelId ?? null,
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

        // Sends updated customLabel for a product to message queue
        foreach ($productsToUpdate as $id => $labels) {
            try {
                $updatedCustomLabels = [];
                $updatedLabelKey = [];
                $removedLabel = false;
                foreach ($labels as $label) {
                    $updatedCustomLabels[$label->getPosition()][] = $label;
                    $updatedLabelKey[$label->getId()] = $label;
                }

                $product = $this->productRepository->search(new Criteria([$id]), $context)->first();
                $usedLabels = $product->getCustomFields()[WexoProductLabels::CUSTOM_LABELS_NAME] ?? [];
                foreach ($usedLabels as $usedLabel) {
                    foreach ($usedLabel as $usedLabelData) {
                        if (isset($updatedLabelKey[$usedLabelData['id']])
                            && !$this->hasLabelChanged($updatedLabelKey[$usedLabelData['id']], $usedLabelData)
                        ) {
                            unset($updatedLabelKey[$usedLabelData['id']]);
                        } elseif (!isset($updatedLabelKey[$usedLabelData['id']])) {
                            $removedLabel = true;
                        }
                    }
                }
                if ($updatedLabelKey || $removedLabel) {
                    $addLabelsToProduct = new ProductLabelsProductEntity(
                        $id,
                        $updatedCustomLabels
                    );
                    $this->bus->dispatch(new Envelope($addLabelsToProduct));
                }
            } catch (\Error | \TypeError | \Exception $e) {
                $this->logEntryRepository->create(
                    [
                        [
                            'message'   => WexoProductLabels::PRODUCT_LABEL_UPDATE_ERROR,
                            'context'   => [
                                'id' => $id ?? null,
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
    }

    /**
     * @param Context $context
     * @return LabelEntity[]
     */
    private function getCustomLabelRules(Context $context)
    {
        $customLabelCriteria = new Criteria();
        $customLabelCriteria->addFilter(new EqualsFilter('active', 1));
        $customLabelCriteria->addAssociation('productStreams');

        return $this->customLabelsRepository->search($customLabelCriteria, $context)->getElements();
    }

    /**
     * @param Context $context
     * @return array
     */
    private function getCurrentProductsLabels(Context $context) :array
    {
        $criteria = new Criteria();
        $criteria->addFilter(
            new NotFilter(
                NotFilter::CONNECTION_OR,
                [
                    new EqualsFilter('customFields.' . WexoProductLabels::CUSTOM_LABELS_NAME, json_encode([])),
                    new EqualsFilter('customFields.' . WexoProductLabels::CUSTOM_LABELS_NAME, null)
                ]
            )
        );

        return $this->productRepository->searchIds($criteria, $context)->getIds();
    }

    /**
     * @param LabelEntity[] $customLabels
     * @param Context $context
     * @return array
     */
    private function getCustomLabelProducts(array $customLabels, Context $context) :array
    {
        $productDefinition = $this->definitionInstanceRegistry->getByEntityName(ProductDefinition::ENTITY_NAME);
        $products = [];

        foreach ($customLabels as $customLabel) {
            try {
                $labelRules = $customLabel->getProductStreams();

                if ($labelRules === null) {
                    continue;
                }

                /** @var ProductStreamEntity $labelRule */
                foreach ($labelRules as $labelRule) {
                    $criteria = new Criteria();
                    $productStreamApiFilter = $labelRule->getApiFilter();
                    $apiFilter = reset($productStreamApiFilter);
                    if ($this->notFilterExist($apiFilter)) {
                        $filter = QueryStringParser::fromArray(
                            $productDefinition,
                            $apiFilter,
                            new SearchRequestException(),
                            '/filter/0'
                        );

                        $criteria->addFilter($filter);
                    } else {
                        $productStreamFilters = $this->productStreamBuilder->buildFilters(
                            $labelRule->getId(),
                            $context
                        );

                        $criteria->addFilter(...$productStreamFilters);
                    }

                    $productIds = $this->productRepository->searchIds($criteria, $context)->getIds();
                    foreach ($productIds as $productId) {
                        $products[$productId][] = $customLabel;
                    }
                }
            } catch (\Error | \TypeError | \Exception $e) {
                $this->logEntryRepository->create(
                    [
                        [
                            'message'   => 'Failed to process label',
                            'context'   => [
                                'label' => $customLabel->getName(),
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

        return $products;
    }

    /**
     * @param array $filter
     * @return bool
     */
    private function notFilterExist(array $filter): bool
    {
        if (isset($filter['type']) && $filter['type'] === 'not') {
            return true;
        }

        if (isset($filter['queries'])) {
            foreach ($filter['queries'] as $query) {
                if ($this->notFilterExist($query)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param LabelEntity $newLabel
     * @param array $existingLabel
     * @return bool
     */
    private function hasLabelChanged(LabelEntity $newLabel, array $existingLabel): bool
    {
        $salesChannels = array_diff(
            $newLabel->getSalesChannelIds() ?? [],
            $existingLabel['salesChannelIds'] ?? []
        );
        return $newLabel->getType() != ($existingLabel['type'] ?? null)
            || $newLabel->getPosition() != ($existingLabel['position'] ?? null)
            || $newLabel->getShape() != ($existingLabel['shape'] ?? null)
            || $newLabel->getColor() != ($existingLabel['color'] ?? null)
            || $newLabel->getContent() != ($existingLabel['content'] ?? null)
            || $newLabel->getImgUrl() != ($existingLabel['imgUrl'] ?? null)
            || $newLabel->getCustomClasses() != ($existingLabel['customClasses'] ?? null)
            || $newLabel->getMarginTop() != ($existingLabel['marginTop'] ?? null)
            || $newLabel->getMarginRight() != ($existingLabel['marginRight'] ?? null)
            || $newLabel->getMarginBottom() != ($existingLabel['marginBottom'] ?? null)
            || $newLabel->getMarginLeft() != ($existingLabel['marginLeft'] ?? null)
            || $newLabel->getHeight() != ($existingLabel['height'] ?? null)
            || $salesChannels
            || $newLabel->getWidth() != ($existingLabel['width'] ?? null);
    }
}
