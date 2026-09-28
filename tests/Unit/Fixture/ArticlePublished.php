<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Tests\Unit\Fixture;

use AlexandreBulete\DddFoundation\Domain\Event\DomainEvent;

final readonly class ArticlePublished implements DomainEvent
{
    public function __construct(
        public string $title,
    ) {}
}
