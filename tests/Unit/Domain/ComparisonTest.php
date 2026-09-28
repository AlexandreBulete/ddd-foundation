<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Tests\Unit\Domain;

use AlexandreBulete\DddFoundation\Domain\Repository\Comparison;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ComparisonTest extends TestCase
{
    #[Test]
    public function aliases_resolve_to_one_canonical_name(): void
    {
        self::assertSame(Comparison::NOT_IN, Comparison::canonical('notIn'));
        self::assertSame(Comparison::NOT_IN, Comparison::canonical('nin'));
        self::assertSame(Comparison::CONTAINS, Comparison::canonical('like'));
        self::assertSame(Comparison::EQ, Comparison::canonical('equals'));
        self::assertSame(Comparison::IS_NULL, Comparison::canonical('is_null'));
    }

    #[Test]
    public function an_unknown_type_is_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Comparison::canonical('regex');
    }

    #[Test]
    public function a_criterion_is_blank_unless_it_asserts_null_ness(): void
    {
        self::assertTrue(Comparison::isBlank(Comparison::parse('')));
        self::assertTrue(Comparison::isBlank(Comparison::parse(['type' => 'eq', 'value' => null])));
        self::assertFalse(Comparison::isBlank(Comparison::parse(['type' => 'null'])));
        self::assertFalse(Comparison::isBlank(Comparison::parse(0)));
    }
}
