<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Tests\Unit\Application;

use AlexandreBulete\DddFoundation\Application\Authorization\Permission;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PermissionTest extends TestCase
{
    #[Test]
    public function a_permission_is_dotted_snake_case(): void
    {
        self::assertSame('client.read', (new Permission('client.read'))->id);
    }

    #[Test]
    public function anything_else_is_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Permission('Client Read');
    }
}
