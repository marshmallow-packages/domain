<?php

namespace Marshmallow\Domain;

use Illuminate\Support\Facades\Http;
use Marshmallow\Domain\Facades\TransIP;

class Domain
{
    public function available($domain)
    {
        $response = TransIP::availability($domain);
        $availability = $response->json()['availability']['status'];

        return ($availability === 'free');
    }

    public function whois($domain)
    {
        $response = TransIP::whois($domain);
        return $response->json()['whois'];
    }

    public function tld($tld)
    {
        $response = TransIP::tld($tld);
        return $response->json()['tld'];
    }

    public function buy($domain)
    {
        $response = TransIP::buy($domain);
        return (empty($response->json()));
    }
}
