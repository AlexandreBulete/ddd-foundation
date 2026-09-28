<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Tests\Unit\Domain;

use AlexandreBulete\DddFoundation\Tests\Unit\Fixture\Article;
use AlexandreBulete\DddFoundation\Tests\Unit\Fixture\ArticleId;
use AlexandreBulete\DddFoundation\Tests\Unit\Fixture\ArticlePublished;
use AlexandreBulete\DddFoundation\Tests\Unit\Fixture\Status;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RecordsEventsTest extends TestCase
{
    #[Test]
    public function recorded_events_are_released_once(): void
    {
        $article = new Article(ArticleId::generate(), 'alpha', 0);
        $article->publish('2026-01-01');

        self::assertEquals([new ArticlePublished('alpha')], $article->releaseEvents());
        self::assertSame([], $article->releaseEvents());
    }

    #[Test]
    public function a_selectable_enum_lists_its_values(): void
    {
        self::assertSame(['draft' => 'draft', 'published' => 'published'], Status::choices());
    }
}
