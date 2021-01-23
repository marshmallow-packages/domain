<?php

namespace Marshmallow\Deployer\Tests;

use Marshmallow\Deployer\Console\InstallCommand;

/**
 * @property EloquentBuilder eloquentBuilder
 */
class DeployWorkFlowTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_get_the_deploy_production_stub()
    {
        $installer = new InstallCommand;
        $stub = $installer->getDeployProductionStub([]);
        $this->assertTrue(
            ($stub !== '' && $stub != false && $stub != null)
        );
    }
}
