<?php declare(strict_types=1);

namespace Wexo\ProductLabels\Core\Content\Label;

use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Content\ProductStream\ProductStreamDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\JsonField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;
use Shopware\Storefront\Theme\Aggregate\ThemeSalesChannelDefinition;
use Wexo\ProductLabels\Core\Content\LabelRuleRelation\LabelRuleRelationDefinition;

class LabelDefinition extends EntityDefinition
{
    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return 'custom_labels';
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return LabelEntity::class;
    }

    /**
     * @return string
     */
    public function getCollectionClass(): string
    {
        return LabelCollection::class;
    }

    /**
     * @return FieldCollection
     */
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new BoolField('active', 'active'))->addFlags(new Required()),
            (new StringField('type', 'type'))->addFlags(new Required()),
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new StringField('position', 'position'))->addFlags(new Required()),
            (new StringField('shape', 'shape')),
            (new StringField('color', 'color')),
            (new StringField('content', 'content')),
            (new StringField('img_url', 'imgUrl')),
            (new StringField('custom_classes', 'customClasses')),
            (new StringField('margin_top', 'marginTop', 10)),
            (new StringField('margin_right', 'marginRight', 10)),
            (new StringField('margin_bottom', 'marginBottom', 10)),
            (new StringField('margin_left', 'marginLeft', 10)),
            (new StringField('height', 'height', 10)),
            (new StringField('width', 'width', 10)),
            (new JsonField('sales_channel_ids', 'salesChannelIds')),
            new FkField('media_id', 'mediaId', MediaDefinition::class),

            (new ManyToOneAssociationField(
                'media',
                'media_id',
                MediaDefinition::class,
                'id',
                true
            )),
            new ManyToManyAssociationField(
                'productStreams',
                ProductStreamDefinition::class,
                LabelRuleRelationDefinition::class,
                'label_id',
                'product_stream_id'
            )
        ]);
    }
}
