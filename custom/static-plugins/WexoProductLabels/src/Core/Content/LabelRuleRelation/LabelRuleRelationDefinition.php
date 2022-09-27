<?php declare(strict_types=1);

namespace Wexo\ProductLabels\Core\Content\LabelRuleRelation;

use Shopware\Core\Content\ProductStream\ProductStreamDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;
use Shopware\Storefront\Theme\ThemeDefinition;
use Wexo\ProductLabels\Core\Content\Label\LabelDefinition;

class LabelRuleRelationDefinition extends MappingEntityDefinition
{
    public const ENTITY_NAME = 'label_rule_relation';

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    /**
     * @return FieldCollection
     */
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new FkField(
                'product_stream_id',
                'productStreamId',
                ProductStreamDefinition::class
            ))->addFlags(new Required(), new PrimaryKey()),
            (new FkField('label_id', 'labelId', LabelDefinition::class))->addFlags(new Required(), new PrimaryKey()),
            new ManyToOneAssociationField('label', 'label_id', LabelDefinition::class),
            new ManyToOneAssociationField('productStream', 'product_stream_id', ProductStreamDefinition::class),
        ]);
    }
}
