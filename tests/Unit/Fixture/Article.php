<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Tests\Unit\Fixture;

use AlexandreBulete\DddFoundation\Domain\Model\RecordsEvents;

final class Article
{
    use RecordsEvents;

    /**
     * @param list<string> $tags
     */
    public function __construct(
        public ArticleId $id,
        public string $title,
        public int $views,
        public ?string $publishedAt = null,
        public array $tags = [],
    ) {}

    public function publish(string $at): void
    {
        $this->publishedAt = $at;
        $this->recordEvent(new ArticlePublished($this->title));
    }
}
