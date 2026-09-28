<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Tests\Unit\Infrastructure;

use AlexandreBulete\DddFoundation\Application\Criteria\CriteriaBuilder;
use AlexandreBulete\DddFoundation\Tests\Unit\Fixture\Article;
use AlexandreBulete\DddFoundation\Tests\Unit\Fixture\ArticleId;
use AlexandreBulete\DddFoundation\Tests\Unit\Fixture\InMemoryArticleRepository;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InMemoryRepositoryTest extends TestCase
{
    private InMemoryArticleRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new InMemoryArticleRepository();
        $this->repository->add(
            new Article(ArticleId::generate(), 'alpha', 10, '2026-01-01', ['php']),
            new Article(ArticleId::generate(), 'beta', 30, null, ['go']),
            new Article(ArticleId::generate(), 'gamma', 20, '2026-02-01', ['php', 'go']),
            new Article(ArticleId::generate(), 'delta', 20, null),
        );
    }

    /**
     * @return iterable<string, array{array<string, mixed>, list<string>}>
     */
    public static function filters(): iterable
    {
        $c = new CriteriaBuilder();

        yield 'plain value = equality' => [['views' => 20], ['delta', 'gamma']];
        yield 'eq' => [$c->eq('title', 'beta'), ['beta']];
        yield 'neq' => [$c->neq('views', 20), ['alpha', 'beta']];
        yield 'lt' => [$c->lt('views', 20), ['alpha']];
        yield 'gte' => [$c->gte('views', 20), ['beta', 'delta', 'gamma']];
        yield 'in' => [$c->in('title', ['alpha', 'delta']), ['alpha', 'delta']];
        yield 'not in (builder spelling)' => [$c->notIn('title', ['alpha', 'delta']), ['beta', 'gamma']];
        yield 'like = contains' => [$c->like('title', 'et'), ['beta']];
        yield 'not like' => [$c->notLike('title', 'a'), []];
        yield 'starts with' => [['title' => ['type' => 'starts_with', 'value' => 'ga']], ['gamma']];
        yield 'member of' => [['tags' => ['type' => 'member_of', 'value' => 'go']], ['beta', 'gamma']];
        yield 'is null, no value needed' => [['publishedAt' => ['type' => 'is_null']], ['beta', 'delta']];
        yield 'blank value = filter skipped' => [['title' => ''], ['alpha', 'beta', 'delta', 'gamma']];
        yield 'criteria combine with AND' => [['views' => 20, 'publishedAt' => ['type' => 'is_not_null']], ['gamma']];
    }

    /**
     * @param array<string, mixed> $filter
     * @param list<string>         $expected
     */
    #[Test]
    #[DataProvider('filters')]
    public function it_filters_like_a_database_would(array $filter, array $expected): void
    {
        $titles = $this->titles($this->repository->filter($filter)->orderBy('title', 'asc'));

        self::assertSame($expected, $titles);
    }

    #[Test]
    public function orderings_accumulate_first_call_first(): void
    {
        $sorted = $this->repository->orderBy('views', 'desc')->orderBy('title', 'asc');

        self::assertSame(['beta', 'delta', 'gamma', 'alpha'], $this->titles($sorted));
    }

    #[Test]
    public function a_page_holds_its_slice_while_count_stays_the_total(): void
    {
        $page = $this->repository->orderBy('title', 'asc')->withPagination(2, 3);

        self::assertSame(['gamma'], $this->titles($page));
        self::assertCount(4, $page, 'count() is the total, as the Doctrine repository answers');

        $paginator = $page->paginator();
        self::assertNotNull($paginator);
        self::assertSame(4, $paginator->getTotalItems());
        self::assertSame(2, $paginator->getLastPage());
        self::assertCount(1, $paginator);
    }

    #[Test]
    public function value_objects_compare_by_value(): void
    {
        $id = ArticleId::generate();
        $this->repository->add(new Article($id, 'epsilon', 0));

        $found = $this->repository->filter(['id' => ArticleId::fromString((string) $id)]);

        self::assertSame(['epsilon'], $this->titles($found));
    }

    #[Test]
    public function an_unknown_field_is_an_error_not_an_empty_result(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->repository->filter(['author' => 'ada']);
    }

    /**
     * @param iterable<Article> $articles
     *
     * @return list<string>
     */
    private function titles(iterable $articles): array
    {
        $titles = [];
        foreach ($articles as $article) {
            $titles[] = $article->title;
        }

        return $titles;
    }
}
