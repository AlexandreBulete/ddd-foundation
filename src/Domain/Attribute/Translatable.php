<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Domain\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final readonly class Translatable
{
    public function __construct(
        public ?string $fallbackLocale = null,
        public bool $required = true,
    ) {}
}