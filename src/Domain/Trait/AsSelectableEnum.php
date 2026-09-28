<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Domain\Trait;

/**
 * For a backed enum: its values as form choices (label = value).
 *
 * @phpstan-require-implements \BackedEnum
 */
trait AsSelectableEnum
{
    /**
     * @return array<int|string, int|string>
     */
    public static function choices(): array
    {
        $choices = [];
        foreach (self::cases() as $case) {
            $choices[$case->value] = $case->value;
        }
        return $choices;
    }
}