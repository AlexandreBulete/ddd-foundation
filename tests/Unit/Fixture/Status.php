<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Tests\Unit\Fixture;

use AlexandreBulete\DddFoundation\Domain\Trait\AsSelectableEnum;

enum Status: string
{
    use AsSelectableEnum;

    case DRAFT = 'draft';
    case PUBLISHED = 'published';
}
