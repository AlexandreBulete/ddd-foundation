<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Domain\Model;

use AlexandreBulete\DddFoundation\Domain\Event\DomainEvent;

trait RecordsEvents
{
    /** @var list<DomainEvent> */
    private array $recordedEvents = [];

    protected function recordEvent(DomainEvent $event): void
    {
        $this->recordedEvents[] = $event;
    }

    /** @return list<DomainEvent> */
    public function releaseEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];

        return $events;
    }
}

