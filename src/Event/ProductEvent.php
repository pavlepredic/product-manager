<?php

namespace App\Event;

use App\Entity\Product;
use App\Serialization\SerializableInterface;

abstract class ProductEvent implements EventInterface
{
    /**
     * @var Product
     */
    private $product;

    /**
     * ProductCreated constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return Product
     */
    public function getPayload()
    {
        return $this->product;
    }
}