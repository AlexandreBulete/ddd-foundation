<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Application\Event;

use AlexandreBulete\DddFoundation\Domain\Event\DomainEvent;

interface EventDispatcherInterface
{
    public function dispatch(DomainEvent $event): void;
}

