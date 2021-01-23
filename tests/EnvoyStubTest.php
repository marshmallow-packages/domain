<?php

namespace Marshmallow\Deployer\Tests;

use Marshmallow\Deployer\Console\InstallCommand;

/**
 * @property EloquentBuilder eloquentBuilder
 */
class EnvoyStubTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_get_the_envoy_stub()
    {
        $installer = new InstallCommand;
        $stub = $installer->getEnvoyStub([]);
        $this->assertTrue(
            ($stub !== '' && $stub != false && $stub != null)
        );
    }
}
