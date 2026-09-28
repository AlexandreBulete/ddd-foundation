<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Tests\Unit\Fixture;

use AlexandreBulete\DddFoundation\Application\Handler\QueryCollectionHandler;
use AlexandreBulete\DddFoundation\Domain\Repository\RepositoryInterface;

/**
 * @extends QueryCollectionHandler<Article>
 */
final readonly class ListArticlesHandler extends QueryCollectionHandler
{
    /**
     * @return RepositoryInterface<Article>
     */
    public function __invoke(ListArticles $query): RepositoryInterface
    {
        return $this->build($query);
    }
}
