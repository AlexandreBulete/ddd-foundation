<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Infrastructure\InMemory;

use AlexandreBulete\DddFoundation\Domain\Repository\Comparison;
use AlexandreBulete\DddFoundation\Domain\Repository\PaginatorInterface;
use AlexandreBulete\DddFoundation\Domain\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * Repository kept in an array — the test double of a Doctrine repository for
 * handler tests. It must answer like one, or the tests prove nothing:
 *
 * - filter() speaks the {@see Comparison} vocabulary, blank criteria skipped;
 * - orderBy() accumulates, the first call being the primary key;
 * - count() is the total of matching entities, paginated or not.
 *
 * Fields are read as public properties (asymmetric `public private(set)`
 * included), exactly the ones a DQL path would name.
 *
 * @template T of object
 *
 * @implements RepositoryInterface<T>
 */
abstract class InMemoryRepository implements RepositoryInterface
{
    /**
     * @var array<string, T>
     */
    protected array $entities = [];

    protected ?int $page = null;
    protected ?int $itemsPerPage = null;

    /** @var list<array{string, 'asc'|'desc'}> */
    private array $orderings = [];

    /**
     * @return \Iterator<array-key, T>
     */
    public function getIterator(): \Iterator
    {
        if (null !== $paginator = $this->paginator()) {
            yield from $paginator;

            return;
        }

        yield from $this->sorted();
    }

    public function withPagination(int $page, int $itemsPerPage): static
    {
        Assert::positiveInteger($page);
        Assert::positiveInteger($itemsPerPage);

        $cloned = clone $this;
        $cloned->page = $page;
        $cloned->itemsPerPage = $itemsPerPage;

        return $cloned;
    }

    public function withoutPagination(): static
    {
        $cloned = clone $this;
        $cloned->page = null;
        $cloned->itemsPerPage = null;

        return $cloned;
    }

    /**
     * @return PaginatorInterface<T>|null
     */
    public function paginator(): ?PaginatorInterface
    {
        if (null === $this->page || null === $this->itemsPerPage) {
            return null;
        }

        $items = $this->sorted();

        return new InMemoryPaginator(new \ArrayIterator($items), count($items), $this->page, $this->itemsPerPage);
    }

    /**
     * @return int<0, max>
     */
    public function count(): int
    {
        return count($this->entities);
    }

    /**
     * @param array<string, mixed> $filter field => value, or field => {type, value}
     */
    public function filter(array $filter): static
    {
        $cloned = clone $this;

        foreach ($filter as $field => $criterion) {
            $criterion = Comparison::parse($criterion);
            if (Comparison::isBlank($criterion)) {
                continue;
            }

            $cloned->entities = array_filter(
                $cloned->entities,
                static fn (object $entity): bool => self::matches(self::read($entity, $field), $criterion['type'], $criterion['value']),
            );
        }

        return $cloned;
    }

    public function orderBy(string $field, string $direction): static
    {
        Assert::notEmpty($field);
        if ($direction !== 'asc' && $direction !== 'desc') {
            throw new \InvalidArgumentException(sprintf('Sort direction must be "asc" or "desc", "%s" given.', $direction));
        }

        $cloned = clone $this;
        $cloned->orderings[] = [$field, $direction];

        return $cloned;
    }

    /**
     * @return list<T>
     */
    private function sorted(): array
    {
        $entities = array_values($this->entities);
        if ($this->orderings === []) {
            return $entities;
        }

        usort($entities, function (object $a, object $b): int {
            foreach ($this->orderings as [$field, $direction]) {
                $order = self::read($a, $field) <=> self::read($b, $field);
                if ($order !== 0) {
                    return $direction === 'asc' ? $order : -$order;
                }
            }

            return 0;
        });

        return $entities;
    }

    private static function read(object $entity, string $field): mixed
    {
        $fields = get_object_vars($entity);
        if (!array_key_exists($field, $fields)) {
            throw new \InvalidArgumentException(sprintf('%s has no readable field "%s".', $entity::class, $field));
        }

        return $fields[$field];
    }

    private static function matches(mixed $actual, string $type, mixed $expected): bool
    {
        return match ($type) {
            Comparison::EQ => self::same($actual, $expected),
            Comparison::NEQ => !self::same($actual, $expected),
            Comparison::LT => $actual < $expected,
            Comparison::LTE => $actual <= $expected,
            Comparison::GT => $actual > $expected,
            Comparison::GTE => $actual >= $expected,
            Comparison::IN => self::contained($actual, $expected),
            Comparison::NOT_IN => !self::contained($actual, $expected),
            Comparison::CONTAINS => str_contains(self::text($actual), self::text($expected)),
            Comparison::NOT_CONTAINS => !str_contains(self::text($actual), self::text($expected)),
            Comparison::STARTS_WITH => str_starts_with(self::text($actual), self::text($expected)),
            Comparison::ENDS_WITH => str_ends_with(self::text($actual), self::text($expected)),
            Comparison::MEMBER_OF => self::contained($expected, $actual),
            Comparison::IS_NULL => $actual === null,
            Comparison::IS_NOT_NULL => $actual !== null,
            default => throw new \LogicException(sprintf('Comparison "%s" is not implemented in memory.', $type)),
        };
    }

    /**
     * Value objects compare by value, as their database columns would.
     */
    private static function same(mixed $a, mixed $b): bool
    {
        if (is_object($a) && is_object($b) && method_exists($a, 'equals') && $a::class === $b::class) {
            return (bool) $a->equals($b);
        }

        if ($a instanceof \Stringable && $b instanceof \Stringable) {
            return (string) $a === (string) $b;
        }

        return $a === $b;
    }

    private static function contained(mixed $needle, mixed $haystack): bool
    {
        if (!is_iterable($haystack)) {
            throw new \InvalidArgumentException(sprintf('An "in" comparison needs a list, %s given.', get_debug_type($haystack)));
        }

        foreach ($haystack as $candidate) {
            if (self::same($needle, $candidate)) {
                return true;
            }
        }

        return false;
    }

    private static function text(mixed $value): string
    {
        if (is_string($value) || is_int($value) || is_float($value) || $value instanceof \Stringable) {
            return (string) $value;
        }

        throw new \InvalidArgumentException(sprintf('A text comparison needs a string, %s given.', get_debug_type($value)));
    }
}
