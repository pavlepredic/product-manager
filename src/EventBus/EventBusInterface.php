<?php

namespace App\EventBus;

use App\Event\EventInterface;
use App\EventSubscriber\EventSubscriberInterface;

interface EventBusInterface
{
    public function publish(EventInterface $event): void;

    public function subscribe(EventSubscriberInterface $eventSubscriber): void;
}