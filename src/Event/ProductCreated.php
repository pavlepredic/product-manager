<?php

namespace App\Event;

class ProductCreated extends ProductEvent
{
    public function getName(): string
    {
        return 'ProductCreated';
    }
}