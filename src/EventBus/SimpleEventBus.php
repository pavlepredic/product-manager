<?php

namespace App\EventBus;

use App\Event\EventInterface;
use App\EventSubscriber\EventSubscriberInterface;

class SimpleEventBus implements EventBusInterface
{
    /**
     * @var EventSubscriberInterface[]
     */
    private $subscribers;

    /**
     * SimpleEventBus constructor.
     */
    public function __construct()
    {
        $this->subscribers = [];
    }

    public function publish(EventInterface $event): void
    {
        /** @var EventSubscriberInterface $subscriber */
        foreach ($this->subscribers as $subscriber) {
            if ($subscriber->supports($event)) {
                $subscriber->onEvent($event);
            }
        }
    }

    public function subscribe(EventSubscriberInterface $eventSubscriber): void
    {
        $this->subscribers[] = $eventSubscriber;
    }
}