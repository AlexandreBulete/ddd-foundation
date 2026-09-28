<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Tests\Unit\Application;

use AlexandreBulete\DddFoundation\Domain\Exception\EntityNotFoundException;
use AlexandreBulete\DddFoundation\Tests\Unit\Fixture\Article;
use AlexandreBulete\DddFoundation\Tests\Unit\Fixture\ArticleId;
use AlexandreBulete\DddFoundation\Tests\Unit\Fixture\InMemoryArticleRepository;
use AlexandreBulete\DddFoundation\Tests\Unit\Fixture\ListArticles;
use AlexandreBulete\DddFoundation\Tests\Unit\Fixture\ListArticlesHandler;
use AlexandreBulete\DddFoundation\Tests\Unit\Fixture\OnlyGammaNormalizer;
use AlexandreBulete\DddFoundation\Tests\Unit\Fixture\ShowArticle;
use AlexandreBulete\DddFoundation\Tests\Unit\Fixture\ShowArticleHandler;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class QueryHandlersTest extends TestCase
{
    private InMemoryArticleRepository $repository;
    private ArticleId $alphaId;

    protected function setUp(): void
    {
        $this->repository = new InMemoryArticleRepository();
        $this->alphaId = ArticleId::generate();
        $this->repository->add(
            new Article($this->alphaId, 'alpha', 10),
            new Article(ArticleId::generate(), 'beta', 30),
            new Article(ArticleId::generate(), 'gamma', 20),
        );
    }

    #[Test]
    public function a_collection_query_applies_criteria_sorting_and_page(): void
    {
        $handler = new ListArticlesHandler($this->repository);

        $result = $handler(new ListArticles(
            criteria: ['views' => ['type' => 'gte', 'value' => 20], 'title' => ''],
            page: 1,
            itemsPerPage: 1,
            withSorting: ['views' => 'desc'],
        ));

        $titles = [];
        foreach ($result as $article) {
            $titles[] = $article->title;
        }

        self::assertSame(['beta'], $titles);
        self::assertCount(2, $result);
    }

    #[Test]
    public function the_normalizer_runs_before_filtering(): void
    {
        $result = (new ListArticlesHandler($this->repository, new OnlyGammaNormalizer()))(new ListArticles());

        self::assertCount(1, $result);
    }

    #[Test]
    public function a_single_query_finds_by_id_or_throws(): void
    {
        $handler = new ShowArticleHandler($this->repository);

        self::assertSame('alpha', $handler(new ShowArticle($this->alphaId))->title);

        $this->expectException(EntityNotFoundException::class);
        $handler(new ShowArticle(ArticleId::generate()));
    }
}
