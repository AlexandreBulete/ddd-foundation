<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Tests\Unit\Fixture;

use AlexandreBulete\DddFoundation\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<InMemoryArticleRepository>
 */
final readonly class ListArticles implements QueryInterface
{
    /**
     * @param array<string, mixed>  $criteria
     * @param array<string, string> $withSorting
     */
    public function __construct(
        public array $criteria = [],
        public ?int $page = null,
        public ?int $itemsPerPage = null,
        public array $withSorting = [],
    ) {}
}
