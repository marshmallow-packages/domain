<?php

namespace Marshmallow\Deployer\Tests;

use Marshmallow\Deployer\Console\InstallCommand;

/**
 * @property EloquentBuilder eloquentBuilder
 */
class InstallCommandTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->installer = new InstallCommand;
    }

    /** @test */
    public function it_can_run_the_artisan_command()
    {
        $answers = $this->installer->setDefaultsByUrl('marshmallow.dev');

        $this->artisan('deployer:install')
             ->expectsQuestion(__('deployer::installer.question_url'), 'marshmallow.dev')
             ->expectsQuestion(__('deployer::installer.question_repository'), $answers->repository)
             ->expectsQuestion(__('deployer::installer.question_main_branch'), $answers->main_branch)
             ->expectsQuestion(__('deployer::installer.question_server_user_name'), $answers->server_user_name)
             ->expectsQuestion(__('deployer::installer.question_app_directory'), $answers->app_directory)
             ->expectsQuestion(__('deployer::installer.question_ssh_key'), $answers->ssh_key)
             ->expectsQuestion(__('deployer::installer.question_number_of_releases'), $answers->number_of_releases)
             ->expectsOutput(__('deployer::installer.intaller_complete'))
             ->assertExitCode(0);
    }

    /** @test */
    public function it_replaces_the_variables_in_a_stub_with_spaces()
    {
        $stub = $this->installer->getEnvoyStub([
            //
        ]);

        $exists_in_stub = strpos($stub, '{{ repository }}');
        $this->assertTrue(
            ($exists_in_stub !== false)
        );

        $stub = $this->installer->getEnvoyStub([
            'repository' => 'Marshmallow Unit Test Repo',
        ]);
        $exists_in_stub = strpos($stub, '{{ repository }}');
        $replaced_in_stub = strpos($stub, 'Marshmallow Unit Test Repo');
        $this->assertTrue(
            ($exists_in_stub === false)
        );
        $this->assertTrue(
            ($replaced_in_stub !== false)
        );
    }

    /** @test */
    public function it_can_return_a_domain_from_an_url()
    {
        $this->installer->setUrl('marshmallow.dev');
        $this->assertNull($this->installer->getSubDomain());

        $this->installer->setUrl('www.marshmallow.dev');
        $this->assertEquals('www', $this->installer->getSubDomain());

        $this->installer->setUrl('https://www.marshmallow.dev');
        $this->assertEquals('www', $this->installer->getSubDomain('https://www.marshmallow.dev'));

        $this->installer->setUrl('https://www.marshmallow.dev/unit-testing');
        $this->assertEquals('www', $this->installer->getSubDomain('https://www.marshmallow.dev/unit-testing'));
    }

    /** @test */
    public function it_can_guess_the_git_repo_path()
    {
        $this->installer->setUrl('marshmallow.dev');
        $this->assertEquals(
            'git@github.com:mm-customers/marshmallow.dev.git',
            $this->installer->guessRepositoryPath()
        );

        $this->installer->setUrl('beta.marshmallow.dev');
        $this->assertEquals(
            'git@github.com:mm-customers/beta.marshmallow.dev.git',
            $this->installer->guessRepositoryPath()
        );
    }

    /** @test */
    public function it_can_guess_the_ssh_key()
    {
        $this->installer->setUrl('marshmallow.dev');
        $this->assertEquals(
            'id_ecdsa',
            $this->installer->guessSshKey()
        );

        $this->installer->setUrl('beta.marshmallow.dev');
        $this->assertEquals(
            'id_ecdsa_beta',
            $this->installer->guessSshKey()
        );
    }

    /** @test */
    public function it_can_guess_the_app_directory()
    {
        $this->installer->setUrl('marshmallow.dev');
        $this->assertEquals(
            '/srv/www/marshmallow.dev/vhosts/www',
            $this->installer->guessAppDirectory()
        );

        $this->installer->setUrl('beta.marshmallow.dev');
        $this->assertEquals(
            '/srv/www/marshmallow.dev/vhosts/beta',
            $this->installer->guessAppDirectory()
        );
    }

    /** @test */
    public function test_it_can_create_a_file()
    {
        $file_path = app_path('unit-test.json');
        $this->assertFalse(file_exists($file_path));
        $this->installer->createFile($file_path, 'unit-test.json');
        $this->assertTrue(file_exists($file_path));
        unlink($file_path);
        $this->assertFalse(file_exists($file_path));
    }

    /** @test */
    public function test_it_can_recursivly_create_folders()
    {
        $folder_path = app_path('unit-test/.github/workflows');
        $this->assertFalse(file_exists($folder_path));
        $this->installer->createFolder($folder_path);
        $this->assertTrue(file_exists($folder_path));
        rmdir(app_path('unit-test/.github/workflows'));
        $this->assertFalse(file_exists($folder_path));
    }

    /** @test */
    public function test_it_can_create_an_envoy_file()
    {
        /**
         * Check it doesnt exist already
         */
        if (file_exists($this->installer->getEnvoyTargetLocation())) {
            unlink($this->installer->getEnvoyTargetLocation());
        }

        /**
         * Test is can be created.
         */
        $this->installer->setDefaultsByUrl('marshmallow.dev');
        $this->installer->createEnvoyFile();
        $this->assertTrue(file_exists($this->installer->getEnvoyTargetLocation()));

        /**
         * Test is is cleaned up properly
         */
        unlink($this->installer->getEnvoyTargetLocation());
        $this->assertFalse(file_exists($this->installer->getEnvoyTargetLocation()));
    }

    /** @test */
    public function it_can_create_the_deploy_production_workflow_file()
    {
        /**
         * Check it doesnt exist already
         */
        if (file_exists($this->installer->getGithubWorkflowTargetLocation())) {
            unlink($this->installer->getGithubWorkflowTargetLocation());
        }

        /**
         * Test is can be created.
         */
        $this->installer->createGithubWorkflowFile();
        $this->assertTrue(file_exists($this->installer->getGithubWorkflowTargetLocation()));

        /**
         * Test is is cleaned up properly
         */
        unlink($this->installer->getGithubWorkflowTargetLocation());
        $this->assertFalse(file_exists($this->installer->getGithubWorkflowTargetLocation()));
    }
}
