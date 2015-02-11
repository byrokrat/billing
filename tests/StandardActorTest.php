<?php

namespace byrokrat\billing;

class StandardActorTest extends \PHPUnit_Framework_TestCase
{
    public function testName()
    {
        $this->assertEquals(
            'foobar',
            (new StandardActor('foobar'))->getName()
        );
    }
}
