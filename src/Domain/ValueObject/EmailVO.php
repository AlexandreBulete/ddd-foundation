<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Domain\ValueObject;

use Webmozart\Assert\Assert;

readonly class EmailVO extends StringVO
{
    protected function validate(string $value): void
    {
        $trimmed = trim($value);
        Assert::stringNotEmpty($trimmed, 'Email cannot be empty');
        Assert::email($trimmed, 'Email must be a valid email address');
    }
}

