<?php

namespace App\Event;

class ProductModified extends ProductEvent
{
    public function getName(): string
    {
        return 'ProductModified';
    }
}