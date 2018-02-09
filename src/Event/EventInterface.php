<?php

namespace App\Event;

use App\Serialization\SerializableInterface;

interface EventInterface
{
    public function getName(): string;
    public function getPayload();
}