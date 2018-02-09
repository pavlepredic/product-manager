<?php

namespace App\EventSubscriber;

use App\Entity\Product;
use App\Event\EventInterface;
use App\Event\ProductModified;

class ProductModifiedSubscriber extends ProductEventSubscriber
{
    public function onEvent(EventInterface $event): void
    {
        /** @var Product $storedProduct */
        /** @var Product $updatedProduct */
        $updatedProduct = $storedProduct = $event->getPayload();
        if (!$this->getEntityManager()->contains($updatedProduct)) {
            $storedProduct = $this->getEntityManager()->getRepository(Product::class)->find($updatedProduct->getId());
            $storedProduct->setPrice($updatedProduct->getPrice());
            $storedProduct->setName($updatedProduct->getName());
        }

        $this->getEntityManager()->flush($storedProduct);
    }

    public function supports(EventInterface $event): bool
    {
        return $event instanceof ProductModified;
    }
}