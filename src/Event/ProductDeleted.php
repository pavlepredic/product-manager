<?php

namespace App\Event;

class ProductDeleted extends ProductEvent
{
    public function getName(): string
    {
        return 'ProductDeleted';
    }
}