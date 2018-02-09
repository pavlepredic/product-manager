<?php

namespace App\EventSubscriber;

use App\Entity\Event as EventEntity;
use App\Event\EventInterface;
use Doctrine\ORM\EntityManagerInterface;

class StoreEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * StoreEventSubscriber constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onEvent(EventInterface $event): void
    {
        $eventEntity = new EventEntity();
        $eventEntity->setPayload(serialize($event));
        $eventEntity->setDate(new \DateTime());
        $eventEntity->setType($event->getName());

        $this->entityManager->persist($eventEntity);
        $this->entityManager->flush($eventEntity);
    }

    public function supports(EventInterface $event): bool
    {
        return true;
    }
}