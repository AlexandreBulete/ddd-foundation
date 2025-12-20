<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Infrastructure\InMemory;

use AlexandreBulete\DddFoundation\Domain\Repository\PaginatorInterface;
use AlexandreBulete\DddFoundation\Domain\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

/**
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

    public function getIterator(): \Iterator
    {
        if (null !== $paginator = $this->paginator()) {
            yield from $paginator;

            return;
        }

        yield from $this->entities;
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

    public function paginator(): ?PaginatorInterface
    {
        if (null === $this->page || null === $this->itemsPerPage) {
            return null;
        }

        return new InMemoryPaginator(
            new \ArrayIterator($this->entities),
            count($this->entities),
            $this->page,
            $this->itemsPerPage,
        );
    }

    public function count(): int
    {
        if (null !== $paginator = $this->paginator()) {
            return count($paginator);
        }

        return count($this->entities);
    }

    public function filter(array $filter): static
    {
        $cloned = clone $this;
        $cloned->entities = array_filter($cloned->entities, function ($entity) use ($filter) {
            foreach ($filter as $key => $value) {
                if ($entity->{$key} !== $value) {
                    return false;
                }
            }

            return true;
        });

        return $cloned;
    }

    public function orderBy(string $field, string $direction): static
    {
        Assert::notEmpty($field);
        Assert::notEmpty($direction);
        Assert::oneOf($direction, ['asc', 'desc']);

        $cloned = clone $this;
        usort($cloned->entities, function ($a, $b) use ($field, $direction) {
            return $direction === 'asc' ? $a->{$field} <=> $b->{$field} : $b->{$field} <=> $a->{$field};
        });

        return $cloned;
    }
}

