<?php

declare(strict_types=1);

namespace byrokrat\billing;

class StandardActorTest extends BaseTestCase
{
    public function testName()
    {
        $this->assertEquals(
            'foobar',
            (new StandardActor('foobar'))->getName()
        );
    }
}
