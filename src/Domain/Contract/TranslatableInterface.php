<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Domain\Contract;

use AlexandreBulete\DddFoundation\Domain\ValueObject\LocaleCode;
use AlexandreBulete\DddFoundation\Domain\ValueObject\TranslationCollection;

interface TranslatableInterface
{
    public function translations(string $field): TranslationCollection;
    
    public function translate(string $field, LocaleCode $locale): ?string;
}