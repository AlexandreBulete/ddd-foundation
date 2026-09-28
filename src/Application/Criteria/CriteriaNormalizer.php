<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Application\Criteria;

use AlexandreBulete\DddFoundation\Domain\Repository\Comparison;

/**
 * Base normalizer: drops blank criteria. Extend it to rename fields or merge
 * defaults with {@see mergeCriteria()}.
 */
abstract readonly class CriteriaNormalizer implements CriteriaNormalizerInterface
{
    public function normalize(array $criteria): array
    {
        return $this->dropEmptyValues($criteria);
    }

    /**
     * Same rule as the repositories: an empty value is a filter left blank,
     * except for null-ness assertions, which carry no value by design.
     *
     * @param array<string, mixed> $criteria
     *
     * @return array<string, mixed>
     */
    protected function dropEmptyValues(array $criteria): array
    {
        return array_filter(
            $criteria,
            static fn (mixed $criterion): bool => !Comparison::isBlank(Comparison::parse($criterion)),
        );
    }

    /**
     * @param array<string, mixed> $criteria
     * @param array<string, mixed> $overrides
     *
     * @return array<string, mixed>
     */
    protected function mergeCriteria(array $criteria, array $overrides): array
    {
        return array_replace($criteria, $overrides);
    }
}
