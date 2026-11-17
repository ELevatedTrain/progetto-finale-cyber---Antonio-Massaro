<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class HttpService
{
    protected $client;
    protected $allowedDomains = ['internal.finance', 'newsapi.org'];
    protected $allowedProtocols = ['http', 'https'];
    protected $refererHeader;

    public function __construct()
    {
        $this->refererHeader = config('app.url');
        $this->client = new Client();
    }

    public function getRequest($url)
    {
        $parsedUrl = parse_url($url);
        $scheme = strtolower($parsedUrl['scheme'] ?? '');
        $host = strtolower($parsedUrl['host'] ?? '');

        if (!in_array($scheme, $this->allowedProtocols, true)) {
            return 'Protocol not allowed';
        }

        if (!in_array($host, $this->allowedDomains, true)) {
            return 'Domain not allowed';
        }

        if ($host === 'internal.finance' && !auth()->user()?->is_admin) {
            abort(403, 'Accesso alla Financial App riservato agli amministratori.');
        }

        $options = [
            'allow_redirects' => false,
        ];

        if ($host === 'internal.finance') {
            $options['headers'] = ['Referer' => $this->refererHeader];
        }

        try {
            $response = $this->client->request('GET', $url, $options);

            if ($response->getStatusCode() >= 300) {
                return 'Unexpected HTTP response';
            }

            return $response->getBody()->getContents();
        } catch (GuzzleException) {
            return 'Something went wrong';
        }
    }
}