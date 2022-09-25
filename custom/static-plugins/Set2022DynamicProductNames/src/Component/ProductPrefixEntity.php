<?php declare(strict_types=1);

namespace Set2022DynamicProductNames\Component;

class ProductPrefixEntity
{
    protected bool $force;

    /**
     * ProductPrefixEntity constructor.
     *
     * Initialize with required fields
     *
     * @param string $productId
     * @param string $action
     */
    public function __construct(
        private string $productId,
        private string $action
    ) {
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param bool $force
     */
    public function setForce(bool $force): void
    {
        $this->force = $force;
    }

    /**
     * @return bool
     */
    public function getForce(): bool
    {
        return $this->force;
    }
}
