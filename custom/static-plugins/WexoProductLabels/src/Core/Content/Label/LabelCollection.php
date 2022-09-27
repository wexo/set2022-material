<?php declare(strict_types=1);

namespace Wexo\ProductLabels\Core\Content\Label;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class LabelCollection extends EntityCollection
{
    /**
     * @return string
     */
    protected function getExpectedClass(): string
    {
        return LabelEntity::class;
    }
}
