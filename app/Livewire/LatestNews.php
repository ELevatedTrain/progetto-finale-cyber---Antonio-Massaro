<?php

namespace App\Livewire;

use GuzzleHttp\Client;
use Livewire\Component;
use App\Services\HttpService;

class LatestNews extends Component
{
    public $selectedApi;
    public $news;
    protected $httpService;

    public function __construct()
    {
        $this->httpService = app(HttpService::class);
    }

    public function fetchNews()
{
    $this->news = null;
    $country = $this->selectedApi;

    if (!is_string($country) || !in_array($country, ['it', 'gb', 'us'], true)) {
        return;
    }

    $apiKey = config('services.newsapi.api_key');

    if (!is_string($apiKey) || $apiKey === '') {
        return;
    }

    $query = http_build_query([
        'country' => $country,
        'apiKey' => $apiKey,
    ]);

    $response = $this->httpService->getRequest(
        'https://newsapi.org/v2/top-headlines?' . $query
    );

    $this->news = json_decode($response, true);
}
    public function render()
    {
        return view('livewire.latest-news');
    }
}
