<?php

namespace App\EventSubscriber;


use App\Event\EventInterface;

interface EventSubscriberInterface
{
    public function onEvent(EventInterface $event): void;

    public function supports(EventInterface $event): bool;
}