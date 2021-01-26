<?php

namespace Marshmallow\Domain;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Marshmallow\Domain\TransIP\Traits\ApiAuth;
use Marshmallow\Domain\TransIP\Traits\ApiMethods;

class TransIP
{
    use ApiAuth;
    use ApiMethods;

    public function availability($domain): Response
    {
        return $this->get("/domain-availability/{$domain}");
    }

    public function whois($domain): Response
    {
        return $this->get("/domains/{$domain}/whois");
    }

    public function tld($tld): Response
    {
        $tld = (strpos($tld, '.') === false) ? ".{$tld}" : $tld;
        return $this->get("/tlds/{$tld}");
    }

    public function buy($domain): Response
    {
        return $this->post("/domains", [
            'domainName' => $domain,
        ]);
    }
}
