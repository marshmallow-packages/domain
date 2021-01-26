<?php

namespace Marshmallow\Domain\Console;

use Exception;
use Illuminate\Console\Command;
use Marshmallow\Domain\Facades\Domain;
use Marshmallow\Domain\Facades\TransIP;

class DomainAvailableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:available {domain_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check a domains availablity.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $domain = $this->argument('domain_name');
        $table_content = [];

        $available = Domain::available($domain);

        $table_content[] = [
            $domain,
            ($available) ? __('Yes') : __('No'),
        ];

        $this->table([
            'Domain', 'Available',
        ], $table_content);

        if (! $available) {
            $this->info('This domain is not available anymore.');
            $confirm = $this->confirm('Do you wish to see the `whois` information?');

            if ($confirm) {
                $resposne = shell_exec('whois ' . $domain);
                echo $resposne;
            }
            return 0;
        } else {
            $confirm = $this->confirm('Do you wish to buy this domain?');
            if ($confirm) {
                $domain_parts = explode('.', $domain);
                $tld = $domain_parts[1];
                $tld = Domain::tld($tld);

                $price = $this->formatPrice($tld['price']);
                $recurringPrice = $this->formatPrice($tld['recurringPrice']);

                $this->info(__('Please look at the prices in the table below:'));
                $this->table([
                    __('Name'), __('Price'),
                ],[
                    [
                        __('Price'),
                        $price,
                    ],[
                        __('Recurring price'),
                        $recurringPrice,
                    ]
                ]);

                $this->newLine();
                $this->info('Please confirm one more time that you wish to purchase the');
                $this->info(__('domain `:domain` for `:price`', [
                    'domain' => $domain,
                    'price' => $price,
                ]));
                $this->info(__('with a recurring price of `:recurringPrice`.', [
                    'recurringPrice' => $recurringPrice,
                ]));
                $confirm = $this->confirm(__('Do you wish to continue with the purchase?'));

                if ($confirm) {
                    $status = Domain::buy($domain);
                    if ($status === true) {
                        $this->comment('The domain is purchased!');
                    } else {
                        $this->error('Error while purchasing the domain.');
                    }
                } else {
                    $this->comment('Domain is not purchased.');
                }
            } else {
                $this->comment('Domain is not purchased.');
            }
        }
    }

    protected function formatPrice($cents)
    {
        $price = $cents / 100;
        return '€ ' . number_format($price, 2, '.', ',');
    }
}
