<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Tests\Unit\Domain;

use AlexandreBulete\DddFoundation\Domain\ValueObject\DatetimeVO;
use AlexandreBulete\DddFoundation\Domain\ValueObject\EmailVO;
use AlexandreBulete\DddFoundation\Tests\Unit\Fixture\ArticleId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ValueObjectTest extends TestCase
{
    #[Test]
    public function an_email_is_stored_as_validated(): void
    {
        self::assertSame('ada@example.com', EmailVO::fromString('  ada@example.com ')->value());
    }

    #[Test]
    public function an_invalid_email_is_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        EmailVO::fromString('not-an-email');
    }

    #[Test]
    public function an_identifier_round_trips_through_its_string_form(): void
    {
        $id = ArticleId::generate();

        self::assertTrue($id->equals(ArticleId::fromString($id->toRfc4122())));
        self::assertFalse($id->equals(ArticleId::generate()));
    }

    #[Test]
    public function datetimes_are_equal_when_they_are_the_same_instant(): void
    {
        $paris = DatetimeVO::fromString('2026-06-01 12:00:00 Europe/Paris');
        $utc = DatetimeVO::fromString('2026-06-01 10:00:00 UTC');

        self::assertTrue($paris->equals($utc));
        self::assertFalse($paris->equals(DatetimeVO::fromString('2026-06-01 10:00:00.5 UTC')));
    }
}
