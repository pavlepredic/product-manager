<?php

namespace App\EventSubscriber;

use App\Entity\Product;
use App\Event\EventInterface;
use App\Event\ProductCreated;

class ProductCreatedSubscriber extends ProductEventSubscriber
{
    public function onEvent(EventInterface $event): void
    {
        /** @var Product $product */
        $product = $event->getPayload();
        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush($product);
    }

    public function supports(EventInterface $event): bool
    {
        return $event instanceof ProductCreated;
    }
}