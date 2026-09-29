<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Tests\Unit\Application;

use AlexandreBulete\DddFoundation\Application\Activity\ActivityDescription;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ActivityDescriptionTest extends TestCase
{
    #[Test]
    public function it_keeps_scalar_facts(): void
    {
        $description = new ActivityDescription(
            subjectType: 'mission',
            subjectId: '42',
            summary: 'mission.approved',
            summaryParams: ['client' => 'lapsa'],
            details: ['release' => 'v2.3', 'files' => ['a.php', 'b.php']],
        );

        self::assertSame('42', $description->subjectId);
        self::assertSame(['a.php', 'b.php'], $description->details['files']);
    }

    #[Test]
    public function an_object_cannot_slip_into_the_details(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        // Deliberately outside the declared types: the runtime guard is what is tested.
        new ActivityDescription(details: ['user' => [new \stdClass()]]); // @phpstan-ignore argument.type
    }

    #[Test]
    public function a_subject_is_a_type_and_an_id(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new ActivityDescription(subjectType: 'mission');
    }
}
