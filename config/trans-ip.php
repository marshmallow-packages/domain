<?php

return [

    'private_key_file_name' => 'trans_ip_private_key',

    /**
     * Your login name on the TransIP website.
     *
     * @var string
     */
    'login' => env('TRANS_IP_LOGIN'),

    /**
     * TransIP API endpoint to connect to.
     *
     * e.g.:
     *
     *        'api.transip.nl'
     *        'api.transip.be'
     *        'api.transip.eu'
     *
     * @var string
     */
    'endpoint' => 'api.transip.nl',

    /**
     * API version number
     *
     * @var string
     */
    'version' => 'v6',

    /**
     * Read only mode
     */
    'read_only' => false,

    /**
     * Whether no whitelisted IP address is needed.
     * Set to true when you want to be able to use a token from anywhere
     */
    'global_key' => false,

    /**
     * Default expiration time.
     * The maximum expiration time is one month.
     */
    'expiration_time' => '30 minutes',

    /**
     * The URL for authentication, this should be formatted with the endpoint URL
     */
    'auth_url' => 'https://%s/%s/auth',

    /**
     * The URL for other api requests
     */
    'endpoint_url' => 'https://%s/%s%s',
];
