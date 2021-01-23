<?php

namespace Marshmallow\Domain\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    private $key;

    private $value;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deployer:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the required commands to connect to the Trans IP api.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $key = $this->ask(__('Please enter your private key from your TransIP account:'));

        return 0;
    }
}
