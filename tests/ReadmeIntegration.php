<?php

declare(strict_types=1);

namespace byrokrat\billing;

use hanneskod\readmetester\PHPUnit\AssertReadme;

class ReadmeIntegration extends \PHPUnit_Framework_TestCase
{
    public function testReadmeIntegrationTests()
    {
        if (!class_exists(AssertReadme::CLASS)) {
            $this->markTestSkipped('Readme-tester is not available.');
        }

        (new AssertReadme($this))->assertReadme('README.md');
    }
}
