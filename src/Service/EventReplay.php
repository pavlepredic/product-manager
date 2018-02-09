<?php

namespace App\Service;

use App\EventBus\EventBusInterface;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Event as EventEntity;

class EventReplay
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EventBusInterface
     */
    private $eventBus;

    /**
     * EventReplay constructor.
     * @param EntityManagerInterface $entityManager
     * @param EventBusInterface $eventBus
     */
    public function __construct(EntityManagerInterface $entityManager, EventBusInterface $eventBus)
    {
        $this->entityManager = $entityManager;
        $this->eventBus = $eventBus;
    }

    public function replayUntil(\DateTime $dateTime = null)
    {
        $this->removeProjections();

        /** @var EventRepository $repository */
        $repository = $this->entityManager->getRepository(EventEntity::class);
        $events = $repository->findUntil($dateTime);
        $this->publishEvents($events);
    }

    public function replayWithLimit(int $limit)
    {
        $this->removeProjections();

        /** @var EventRepository $repository */
        $repository = $this->entityManager->getRepository(EventEntity::class);
        $events = $repository->findBy([], ['date' => 'asc'], $limit);
        $this->publishEvents($events);
    }

    private function publishEvents($events)
    {
        /** @var EventEntity $eventEntity */
        foreach ($events as $eventEntity) {
            $event = unserialize($eventEntity->getPayload());
            $this->eventBus->publish($event);
        }
    }

    private function removeProjections()
    {
        //TODO need to abstract this truncating logic somehow
        //I mustn't make assumption about the underlying table structure
        $this->entityManager->getConnection()->query('TRUNCATE product')->execute();
    }

}