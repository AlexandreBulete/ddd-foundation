<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Application\Activity;

/**
 * On a query: journal it — for sensitive reads (a client contract). Queries
 * are not journaled otherwise.
 *
 * `visibleWith`: a permission that also sees the entry, for when being allowed
 * to do something and needing to know it happened differ.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class Journaled
{
    public function __construct(
        public ?string $visibleWith = null,
    ) {}
}
