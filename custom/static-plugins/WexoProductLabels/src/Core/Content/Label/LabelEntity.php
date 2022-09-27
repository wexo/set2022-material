<?php declare(strict_types=1);

namespace Wexo\ProductLabels\Core\Content\Label;

use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;

class LabelEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $position;

    /**
     * @var string|null
     */
    protected $shape;

    /**
     * @var string|null
     */
    protected $color;

    /**
     * @var string|null
     */
    protected $content;

    /**
     * @var string|null
     */
    protected $imgUrl;

    /**
     * @var string|null
     */
    protected $customClasses;

    /**
     * @var string|null
     */
    protected $mediaId;

    /**
     * @var MediaEntity|null
     */
    protected $media;

    /**
     * @var string|null
     */
    protected $marginTop;

    /**
     * @var string|null
     */
    protected $marginRight;

    /**
     * @var string|null
     */
    protected $marginBottom;

    /**
     * @var string|null
     */
    protected $marginLeft;

    /**
     * @var string|null
     */
    protected $height;

    /**
     * @var string|null
     */
    protected $width;

    /**
     * @var EntityCollection|null
     */
    protected $productStreams;

    /**
     * @var array|null
     */
    protected $salesChannelIds;

    /**
     * @return bool
     */
    public function getActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition(string $position): void
    {
        $this->position = $position;
    }

    /**
     * @return string|null
     */
    public function getShape(): ?string
    {
        return $this->shape;
    }

    /**
     * @param string|null $shape
     */
    public function setShape(?string $shape): void
    {
        $this->shape = $shape;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string|null
     */
    public function getImgUrl(): ?string
    {
        return $this->imgUrl;
    }

    /**
     * @param string|null $imgUrl
     */
    public function setImgUrl(?string $imgUrl): void
    {
        $this->imgUrl = $imgUrl;
    }

    /**
     * @return string|null
     */
    public function getCustomClasses(): ?string
    {
        return $this->customClasses;
    }

    /**
     * @param string|null $customClasses
     */
    public function setCustomClasses(?string $customClasses): void
    {
        $this->customClasses = $customClasses;
    }

    /**
     * @return string|null
     */
    public function getMediaId(): ?string
    {
        return $this->mediaId;
    }

    /**
     * @param string|null $mediaId
     */
    public function setMediaId(?string $mediaId): void
    {
        $this->mediaId = $mediaId;
    }

    /**
     * @return MediaEntity|null
     */
    public function getMedia(): ?MediaEntity
    {
        return $this->media;
    }

    /**
     * @param MediaEntity|null $media
     */
    public function setMedia(?MediaEntity $media): void
    {
        $this->media = $media;
    }

    /**
     * @return string|null
     */
    public function getMarginTop(): ?string
    {
        return $this->marginTop;
    }

    /**
     * @param string|null $marginTop
     */
    public function setMarginTop(?string $marginTop): void
    {
        $this->marginTop = $marginTop;
    }

    /**
     * @return string|null
     */
    public function getMarginRight(): ?string
    {
        return $this->marginRight;
    }

    /**
     * @param string|null $marginRight
     */
    public function setMarginRight(?string $marginRight): void
    {
        $this->marginRight = $marginRight;
    }

    /**
     * @return string|null
     */
    public function getMarginBottom(): ?string
    {
        return $this->marginBottom;
    }

    /**
     * @param string|null $marginBottom
     */
    public function setMarginBottom(?string $marginBottom): void
    {
        $this->marginBottom = $marginBottom;
    }

    /**
     * @return string|null
     */
    public function getMarginLeft(): ?string
    {
        return $this->marginLeft;
    }

    /**
     * @param string|null $marginLeft
     */
    public function setMarginLeft(?string $marginLeft): void
    {
        $this->marginLeft = $marginLeft;
    }

    /**
     * @return string|null
     */
    public function getHeight(): ?string
    {
        return $this->height;
    }

    /**
     * @param string|null $height
     */
    public function setHeight(?string $height): void
    {
        $this->height = $height;
    }

    /**
     * @return string|null
     */
    public function getWidth(): ?string
    {
        return $this->width;
    }

    /**
     * @param string|null $width
     */
    public function setWidth(?string $width): void
    {
        $this->width = $width;
    }

    /**
     * @return EntityCollection|null
     */
    public function getProductStreams(): ?EntityCollection
    {
        return $this->productStreams;
    }

    /**
     * @param EntityCollection|null $productStreams
     */
    public function setProductStreams(?EntityCollection $productStreams): void
    {
        $this->productStreams = $productStreams;
    }

    public function getSalesChannelIds(): ?array
    {
        return $this->salesChannelIds;
    }

    public function setSalesChannelIds(?array $salesChannelIds): void
    {
        $this->salesChannelIds = $salesChannelIds;
    }
}
