<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Domain\Repository;

/**
 * The comparison vocabulary of {@see RepositoryInterface::filter()}.
 *
 * One definition shared by every implementation — Doctrine, in-memory — so a
 * criterion means the same thing in production and in a handler test. Each
 * type has one canonical name; the aliases are the spellings that already
 * circulate in projects and stay accepted.
 */
final class Comparison
{
    public const EQ = 'eq';
    public const NEQ = 'neq';
    public const LT = 'lt';
    public const LTE = 'lte';
    public const GT = 'gt';
    public const GTE = 'gte';
    public const IN = 'in';
    public const NOT_IN = 'not_in';
    public const CONTAINS = 'contains';
    public const NOT_CONTAINS = 'not_contains';
    public const STARTS_WITH = 'starts_with';
    public const ENDS_WITH = 'ends_with';
    public const MEMBER_OF = 'member_of';
    public const IS_NULL = 'is_null';
    public const IS_NOT_NULL = 'is_not_null';

    private const ALIASES = [
        'equals' => self::EQ, 'equal' => self::EQ, 'is' => self::EQ,
        'not_equals' => self::NEQ, 'not_equal' => self::NEQ,
        'nin' => self::NOT_IN, 'notIn' => self::NOT_IN,
        'like' => self::CONTAINS,
        'not_like' => self::NOT_CONTAINS, 'notLike' => self::NOT_CONTAINS,
        'startswith' => self::STARTS_WITH,
        'endswith' => self::ENDS_WITH,
        'member_in' => self::MEMBER_OF,
        'isnull' => self::IS_NULL, 'null' => self::IS_NULL,
        'isnotnull' => self::IS_NOT_NULL, 'not_null' => self::IS_NOT_NULL,
    ];

    private const CANONICAL = [
        self::EQ, self::NEQ, self::LT, self::LTE, self::GT, self::GTE,
        self::IN, self::NOT_IN, self::CONTAINS, self::NOT_CONTAINS,
        self::STARTS_WITH, self::ENDS_WITH, self::MEMBER_OF,
        self::IS_NULL, self::IS_NOT_NULL,
    ];

    /**
     * @throws \InvalidArgumentException for a type nobody implements
     */
    public static function canonical(string $type): string
    {
        if (in_array($type, self::CANONICAL, true)) {
            return $type;
        }

        return self::ALIASES[$type]
            ?? throw new \InvalidArgumentException(sprintf('Unsupported comparison type "%s".', $type));
    }

    /**
     * Null-ness assertions take no operand: a criterion of this type must be
     * applied even though its value is empty.
     */
    public static function isValueless(string $type): bool
    {
        $canonical = self::ALIASES[$type] ?? $type;

        return $canonical === self::IS_NULL || $canonical === self::IS_NOT_NULL;
    }

    /**
     * Reads one entry of a filter array: `field => value` is an equality,
     * `field => {type, value}` names its comparison.
     *
     * @return array{type: string, value: mixed}
     */
    public static function parse(mixed $criterion): array
    {
        if (!is_array($criterion)) {
            return ['type' => self::EQ, 'value' => $criterion];
        }

        $type = $criterion['type'] ?? self::EQ;
        if (!is_string($type)) {
            throw new \InvalidArgumentException(sprintf('A comparison type must be a string, %s given.', get_debug_type($type)));
        }

        return ['type' => self::canonical($type), 'value' => $criterion['value'] ?? null];
    }

    /**
     * Whether a parsed criterion carries nothing to compare — a filter left
     * blank, to be skipped rather than turned into `= ''`.
     *
     * @param array{type: string, value: mixed} $criterion
     */
    public static function isBlank(array $criterion): bool
    {
        return !self::isValueless($criterion['type'])
            && ($criterion['value'] === null || $criterion['value'] === '');
    }
}
