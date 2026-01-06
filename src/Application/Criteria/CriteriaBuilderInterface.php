<?php 

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Application\Criteria;

interface CriteriaBuilderInterface
{
    public function eq(string $field, $value): array;
    public function neq(string $field, $value): array;
    public function lt(string $field, $value): array;
    public function lte(string $field, $value): array;
    public function gt(string $field, $value): array;
    public function gte(string $field, $value): array;
    public function in(string $field, array $values): array;
    public function notIn(string $field, array $values): array;
    public function like(string $field, string $value): array;
    public function notLike(string $field, string $value): array;
}