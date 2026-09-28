<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Tests\Unit\Fixture;

use AlexandreBulete\DddFoundation\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<Article>
 */
final readonly class ShowArticle implements QueryInterface
{
    public function __construct(
        public ArticleId $id,
    ) {}
}
