<?php 

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Application\Criteria;

use AlexandreBulete\DddFoundation\Application\Criteria\CriteriaBuilderInterface;

/**
 * TODO: Add support for multiple/merge criteria (merge criteria into a single array) ?
 */
final readonly class CriteriaBuilder implements CriteriaBuilderInterface
{
    public function eq(string $field, $value): array
    {
        return [ $field => ['type' => 'eq', 'value' => $value] ];
    }

    public function neq(string $field, $value): array
    {
        return [ $field => ['type' => 'neq', 'value' => $value] ];
    }

    public function lt(string $field, $value): array
    {
        return [ $field => ['type' => 'lt', 'value' => $value] ];
    }

    public function lte(string $field, $value): array
    {
        return [ $field => ['type' => 'lte', 'value' => $value] ];
    }
    
    public function gt(string $field, $value): array
    {
        return [ $field => ['type' => 'gt', 'value' => $value] ];
    }
    
    public function gte(string $field, $value): array
    {
        return [ $field => ['type' => 'gte', 'value' => $value] ];
    }
    
    public function in(string $field, array $values): array
    {
        return [ $field => ['type' => 'in', 'value' => $values] ];
    }
    
    public function notIn(string $field, array $values): array
    {
        return [ $field => ['type' => 'notIn', 'value' => $values] ];
    }
    
    public function like(string $field, string $value): array
    {
        return [ $field => ['type' => 'like', 'value' => $value] ];
    }
    
    public function notLike(string $field, string $value): array
    {
        return [ $field => ['type' => 'notLike', 'value' => $value] ];
    }
}