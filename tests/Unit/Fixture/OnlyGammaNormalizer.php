<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Tests\Unit\Fixture;

use AlexandreBulete\DddFoundation\Application\Criteria\CriteriaNormalizer;

final readonly class OnlyGammaNormalizer extends CriteriaNormalizer
{
    public function normalize(array $criteria): array
    {
        return $this->mergeCriteria(parent::normalize($criteria), ['title' => 'gamma']);
    }
}
