<?php

namespace Spatie\CalendarLinks\Test;

use DateTime;
use DateTimeZone;
use Spatie\CalendarLinks\Link;
use Spatie\Snapshots\MatchesSnapshots;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use MatchesSnapshots;

    protected function createLink()
    {
        $description = <<<'EOF'
With balloons, clowns and stuff
Bring a dog, bring a frog
EOF;

        return Link::create(
            'Birthday',
            DateTime::createFromFormat('Y-m-d H:i', '2018-02-01 09:00', new DateTimeZone('UTC')),
            DateTime::createFromFormat('Y-m-d H:i', '2018-02-01 18:00', new DateTimeZone('UTC'))
        )->description($description)->address('Party Lane 1A, 1337 Funtown');
    }

    protected function createAlldayLink()
    {
        $description = <<<'EOF'
With balloons, clowns and stuff
Bring a dog, bring a frog
EOF;

        return Link::create(
            'Birthday',
            DateTime::createFromFormat('Y-m-d H:i', '2018-02-01 09:00', new DateTimeZone('UTC')),
            DateTime::createFromFormat('Y-m-d H:i', '2018-02-01 18:00', new DateTimeZone('UTC')),
            true
        )->description($description)->address('Party Lane 1A, 1337 Funtown');
    }
}
