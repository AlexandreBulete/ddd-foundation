<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Application\Criteria;

/**
 * Builds criteria in the shape RepositoryInterface::filter() reads:
 * `field => {type, value}`, types from {@see \AlexandreBulete\DddFoundation\Domain\Repository\Comparison}.
 *
 * @phpstan-type Criteria array<string, array{type: string, value: mixed}>
 */
interface CriteriaBuilderInterface
{
    /** @return Criteria */
    public function eq(string $field, mixed $value): array;

    /** @return Criteria */
    public function neq(string $field, mixed $value): array;

    /** @return Criteria */
    public function lt(string $field, mixed $value): array;

    /** @return Criteria */
    public function lte(string $field, mixed $value): array;

    /** @return Criteria */
    public function gt(string $field, mixed $value): array;

    /** @return Criteria */
    public function gte(string $field, mixed $value): array;

    /**
     * @param list<mixed> $values
     *
     * @return Criteria
     */
    public function in(string $field, array $values): array;

    /**
     * @param list<mixed> $values
     *
     * @return Criteria
     */
    public function notIn(string $field, array $values): array;

    /** @return Criteria */
    public function like(string $field, string $value): array;

    /** @return Criteria */
    public function notLike(string $field, string $value): array;
}
