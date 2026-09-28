<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Domain\Repository;

use AlexandreBulete\DddFoundation\Domain\ValueObject\IdentifierVO;

/**
 * @template T of object
 *
 * @extends \IteratorAggregate<array-key, T>
 */
interface RepositoryInterface extends \IteratorAggregate, \Countable
{
    /**
     * @return \Iterator<array-key, T>
     */
    public function getIterator(): \Iterator;

    public function count(): int;

    /**
     * @return PaginatorInterface<T>|null
     */
    public function paginator(): ?PaginatorInterface;

    public function withPagination(int $page, int $itemsPerPage): static;

    public function withoutPagination(): static;

    public function orderBy(string $field, string $direction): static;

    /**
     * @param array<string, mixed> $filter
     */
    public function filter(array $filter): static;

    /**
     * @return T|null
     */
    public function findById(IdentifierVO $id): ?object;
}

