<?php declare(strict_types=1);

namespace Wexo\ProductLabels\Component;

class ProductLabelsProductEntity
{
    /** @var string */
    protected $productId;
    /** @var array */
    protected $customLabels;

    /**
     * ProductLabelsProductEntity constructor.
     *
     * Initialize with required fields
     *
     * @param string $productId
     * @param array $customLabels
     */
    public function __construct(
        string $productId,
        array $customLabels
    ) {
        $this->productId = $productId;
        $this->customLabels = $customLabels;
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @return array
     */
    public function getCustomLabels(): array
    {
        return $this->customLabels;
    }
}
