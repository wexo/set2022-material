<?php declare(strict_types=1);

namespace Set2022DynamicProductNames;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use Shopware\Core\System\CustomField\CustomFieldTypes;

class Set2022DynamicProductNames extends Plugin
{
    const LOG_CHANNEL = 'dynamic-product-names';
    const ACTION_ADD = 'add';
    const ACTION_REMOVE = 'remove';
    public const CUSTOM_FIELD_SET_NAME = 'product_name_prefix_set';
    public const CUSTOM_FIELD_NAME = 'product_name_prefix';

    public function install(InstallContext $installContext): void
    {
        /** @var EntityRepository $customFieldSetRepository */
        $customFieldSetRepository = $this->container->get("custom_field_set.repository");

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter("name", self::CUSTOM_FIELD_SET_NAME));
        $exists = $customFieldSetRepository->searchIds($criteria, $installContext->getContext())->firstId();

        if (!$exists) {
            $customFieldSetRepository->create(
                [
                    [
                        'name' => self::CUSTOM_FIELD_SET_NAME,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Product Prefix',
                                'de-DE' => 'Produktpräfix'
                            ]
                        ],
                        'relations' => [
                            [
                                'entityName' => 'product'
                            ],
                            [
                                'entityName' => 'category'
                            ]
                        ],
                        'customFields' => [
                            [
                                'name' => self::CUSTOM_FIELD_NAME,
                                'type' => CustomFieldTypes::TEXT,
                                'config' => [
                                    'label' => [
                                        'en-GB' => 'Prefix',
                                        'de-DE' => 'Präfix'
                                    ],
                                    'customFieldType' => CustomFieldTypes::TEXT
                                ],
                            ]
                        ]
                    ]
                ],
                $installContext->getContext()
            );
        }
    }

    public function update(UpdateContext $updateContext): void
    {
    }

    public function activate(ActivateContext $activateContext): void
    {
        // TODO: Activate custom field
    }

    public function deactivate(DeactivateContext $deactivateContext): void
    {
        // TODO: Deactivate custom field
    }

    public function uninstall(UninstallContext $uninstallContext): void
    {
        if ($uninstallContext->keepUserData()) {
            return;
        }
        /** @var EntityRepository $customFieldSetRepository */
        $customFieldSetRepository = $this->container->get("custom_field_set.repository");
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter("name", self::CUSTOM_FIELD_SET_NAME));
        $customFieldSetId = $customFieldSetRepository->searchIds($criteria, $uninstallContext->getContext())->firstId();
        if ($customFieldSetId) {
            $customFieldSetRepository->delete(
                [
                    [
                        'id' => $customFieldSetId
                    ]
                ],
                $uninstallContext->getContext()
            );
        }
        // TODO: Handle data removal.
    }
}
