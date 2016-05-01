<?php

declare(strict_types=1);

namespace byrokrat\billing;

class ReadmeIntegration extends \hanneskod\readmetester\PHPUnit\ReadmeTestCase
{
    public function testReadmeIntegrationTests()
    {
        $this->assertReadme('README.md');
    }
}
