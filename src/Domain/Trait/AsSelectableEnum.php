<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Domain\Trait;

trait AsSelectableEnum
{
    public static function choices(): array
    {
        $choices = [];
        foreach (self::cases() as $case) {
            $choices[$case->value] = $case->value;
        }
        return $choices;
    }
}