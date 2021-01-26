<?php

namespace Marshmallow\Domain\Console;

use Exception;
use Illuminate\Console\Command;
use Marshmallow\Domain\Facades\TransIP;

class InstallCommand extends Command
{
    private $key;

    private $value;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:install';

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
        if (! $key) {
            throw new Exception('You need to add a private key', 1);
        }

        $this->storeKey($key);


        // storage_path('trans_ip_private_key')

        // dd($key);
        return 0;
    }

    protected function storeKey($key)
    {
        $key_file = fopen(TransIP::keyPath(), "w") or die('Unable to open file!');
        fwrite($key_file, $key);
        fclose($key_file);
    }
}
