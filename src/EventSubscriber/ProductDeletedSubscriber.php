<?php

namespace App\EventSubscriber;

use App\Entity\Product;
use App\Event\EventInterface;
use App\Event\ProductDeleted;

class ProductDeletedSubscriber extends ProductEventSubscriber
{
    public function onEvent(EventInterface $event): void
    {
        /** @var Product $product */
        $product = $event->getPayload();
        if (!$this->getEntityManager()->contains($product)) {
            $product = $this->getEntityManager()->getRepository(Product::class)->find($product->getId());
        }
        $this->getEntityManager()->remove($product);
        $this->getEntityManager()->flush($product);
    }

    public function supports(EventInterface $event): bool
    {
        return $event instanceof ProductDeleted;
    }
}