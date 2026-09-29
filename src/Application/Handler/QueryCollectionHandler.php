<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Application\Handler;

use AlexandreBulete\DddFoundation\Application\Criteria\CriteriaNormalizerInterface;
use AlexandreBulete\DddFoundation\Application\Query\QueryInterface;
use AlexandreBulete\DddFoundation\Domain\Repository\RepositoryInterface;

/**
 * Turns a collection query into a configured repository.
 *
 * The query is read by convention, not through an interface: any public
 * `criteria`, `page` + `itemsPerPage` and `withSorting` it declares are
 * applied, the others ignored. A field of the wrong type is an error, never
 * silently skipped.
 *
 * @template T of object
 */
abstract readonly class QueryCollectionHandler
{
    /**
     * @param RepositoryInterface<T> $repository
     */
    public function __construct(
        protected RepositoryInterface $repository,
        protected ?CriteriaNormalizerInterface $criteriaNormalizer = null
    ) {
    }

    /**
     * @param QueryInterface<mixed>       $query
     * @param RepositoryInterface<T>|null $repository a narrowed repository to build
     *                                                on (a visibility, a scope),
     *                                                the handler's own otherwise
     *
     * @return RepositoryInterface<T>
     */
    protected function build(QueryInterface $query, ?RepositoryInterface $repository = null): RepositoryInterface
    {
        $fields = get_object_vars($query);
        $repository ??= $this->repository;

        // Normalized even when empty: a normalizer may add default criteria.
        $criteria = $this->normalize(self::stringKeyed($fields['criteria'] ?? [], 'criteria'));
        if ($criteria !== []) {
            $repository = $repository->filter($criteria);
        }

        $page = $fields['page'] ?? null;
        $itemsPerPage = $fields['itemsPerPage'] ?? null;
        if ($page !== null && $itemsPerPage !== null) {
            if (!is_int($page) || !is_int($itemsPerPage)) {
                throw new \InvalidArgumentException(sprintf('%s: page and itemsPerPage must be integers.', $query::class));
            }
            $repository = $repository->withPagination($page, $itemsPerPage);
        }

        foreach (self::stringKeyed($fields['withSorting'] ?? [], 'withSorting') as $field => $direction) {
            if (!is_string($direction)) {
                throw new \InvalidArgumentException(sprintf('%s: sort direction for "%s" must be a string.', $query::class, $field));
            }
            $repository = $repository->orderBy($field, $direction);
        }

        return $repository;
    }

    /**
     * @param array<string, mixed> $criteria
     *
     * @return array<string, mixed>
     */
    protected function normalize(array $criteria): array
    {
        if (null !== $this->criteriaNormalizer) {
            return $this->criteriaNormalizer->normalize($criteria);
        }

        return $criteria;
    }

    /**
     * @return array<string, mixed>
     */
    private static function stringKeyed(mixed $value, string $name): array
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException(sprintf('Query field "%s" must be an array.', $name));
        }

        $typed = [];
        foreach ($value as $key => $item) {
            $typed[(string) $key] = $item;
        }

        return $typed;
    }
}
