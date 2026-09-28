<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Domain\ValueObject;

use Webmozart\Assert\Assert;

readonly class EmailVO extends StringVO
{
    protected function normalize(string $value): string
    {
        return trim($value);
    }

    protected function validate(string $value): void
    {
        Assert::stringNotEmpty($value, 'Email cannot be empty');
        Assert::email($value, 'Email must be a valid email address');
    }
}

