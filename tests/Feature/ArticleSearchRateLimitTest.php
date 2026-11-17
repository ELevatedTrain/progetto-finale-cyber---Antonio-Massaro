<?php

namespace Tests\Feature;

use Tests\TestCase;

class ArticleSearchRateLimitTest extends TestCase
{
    public function test_search_is_limited_to_ten_requests_per_minute_per_ip(): void
    {
        $url = '/articles/search?query=B';

        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1']);

        for ($requestNumber = 1; $requestNumber <= 10; $requestNumber++) {
            $this->get($url)->assertOk();
        }

        $this->get($url)->assertStatus(429);
    }
}