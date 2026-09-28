<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Application\Criteria;

use AlexandreBulete\DddFoundation\Domain\Repository\Comparison;

/**
 * Emits canonical {@see Comparison} types, the ones every repository
 * implementation understands.
 *
 * @phpstan-import-type Criteria from CriteriaBuilderInterface
 */
final readonly class CriteriaBuilder implements CriteriaBuilderInterface
{
    public function eq(string $field, mixed $value): array
    {
        return self::criterion($field, Comparison::EQ, $value);
    }

    public function neq(string $field, mixed $value): array
    {
        return self::criterion($field, Comparison::NEQ, $value);
    }

    public function lt(string $field, mixed $value): array
    {
        return self::criterion($field, Comparison::LT, $value);
    }

    public function lte(string $field, mixed $value): array
    {
        return self::criterion($field, Comparison::LTE, $value);
    }

    public function gt(string $field, mixed $value): array
    {
        return self::criterion($field, Comparison::GT, $value);
    }

    public function gte(string $field, mixed $value): array
    {
        return self::criterion($field, Comparison::GTE, $value);
    }

    public function in(string $field, array $values): array
    {
        return self::criterion($field, Comparison::IN, $values);
    }

    public function notIn(string $field, array $values): array
    {
        return self::criterion($field, Comparison::NOT_IN, $values);
    }

    public function like(string $field, string $value): array
    {
        return self::criterion($field, Comparison::CONTAINS, $value);
    }

    public function notLike(string $field, string $value): array
    {
        return self::criterion($field, Comparison::NOT_CONTAINS, $value);
    }

    /**
     * @return Criteria
     */
    private static function criterion(string $field, string $type, mixed $value): array
    {
        return [$field => ['type' => $type, 'value' => $value]];
    }
}
