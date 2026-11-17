<?php

namespace Tests\Feature;

use App\Livewire\LatestNews;
use App\Services\HttpService;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class LatestNewsTest extends TestCase
{
    public function test_fetch_news_rejects_an_unsupported_country_before_calling_http(): void
    {
        config(['services.newsapi.api_key' => 'test-key']);

        $httpService = Mockery::mock(HttpService::class);
        $httpService->shouldNotReceive('getRequest');
        $this->app->instance(HttpService::class, $httpService);

        Livewire::test(LatestNews::class)
            ->set('selectedApi', 'zz')
            ->call('fetchNews')
            ->assertSet('news', null);
    }
}