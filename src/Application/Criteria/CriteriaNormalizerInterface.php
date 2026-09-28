<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Application\Criteria;

/**
 * Rewrites raw criteria (a grid's filters, an API query string) into what the
 * repository filters on — dropping blanks, renaming fields, adding defaults.
 */
interface CriteriaNormalizerInterface
{
    /**
     * @param array<string, mixed> $criteria
     *
     * @return array<string, mixed>
     */
    public function normalize(array $criteria): array;
}
