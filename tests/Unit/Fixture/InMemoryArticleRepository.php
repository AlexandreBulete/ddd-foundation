<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Tests\Unit\Fixture;

use AlexandreBulete\DddFoundation\Domain\ValueObject\IdentifierVO;
use AlexandreBulete\DddFoundation\Infrastructure\InMemory\InMemoryRepository;

/**
 * @extends InMemoryRepository<Article>
 */
final class InMemoryArticleRepository extends InMemoryRepository
{
    public function add(Article ...$articles): void
    {
        foreach ($articles as $article) {
            $this->entities[(string) $article->id] = $article;
        }
    }

    public function findById(IdentifierVO $id): ?Article
    {
        return $this->entities[(string) $id] ?? null;
    }
}
