<?php

declare(strict_types=1);

namespace AlexandreBulete\DddFoundation\Application\Activity;

/**
 * Implemented by a command (or a journaled query) that wants more than the
 * defaults in the activity journal: its subject, a readable sentence, details.
 * Without it, the entry still records who, what, when, and how it ended.
 *
 * Must be pure: it reads the message, nothing else.
 */
interface JournaledInterface
{
    public function describeActivity(): ActivityDescription;
}
