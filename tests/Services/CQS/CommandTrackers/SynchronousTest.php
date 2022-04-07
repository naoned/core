<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandTrackers;

use PHPUnit\Framework\TestCase;

class SynchronousTest extends TestCase
{
    public function testRetrieveTrackedData()
    {
        $tracker = new Synchronous();

        $trackingId = 'pony-burger';

        $data = new class {};

        $tracker->track($trackingId, $data);

        $this->assertSame($data, $tracker->retrieveTrackedData($trackingId));
    }

    public function testTrackSameIdMoreThanOnce()
    {
        $this->expectException(\LogicException::class);
        $tracker = new Synchronous();

        $tracker->track('pony-burger', []);
        $tracker->track('pony-burger', []);
    }

    public function testRetrieveUnknownTrackedData()
    {
        $this->expectException(\RuntimeException::class);
        $tracker = new Synchronous();

        $tracker->retrieveTrackedData('pony-burger');
    }
}
