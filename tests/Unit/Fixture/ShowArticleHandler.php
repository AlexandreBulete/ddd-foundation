<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Tests\Unit\Fixture;

use AlexandreBulete\DddFoundation\Application\Handler\QuerySingleHandler;

/**
 * @extends QuerySingleHandler<Article>
 */
final readonly class ShowArticleHandler extends QuerySingleHandler
{
    public function __invoke(ShowArticle $query): Article
    {
        return $this->build($query);
    }
}
