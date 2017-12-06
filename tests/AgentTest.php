<?php

declare(strict_types=1);

namespace byrokrat\billing;

class AgentTest extends TestCase
{
    public function testName()
    {
        $this->assertEquals(
            'foobar',
            (new Agent('foobar'))->getName()
        );
    }
}
