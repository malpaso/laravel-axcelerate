<?php

namespace malpaso\LaravelAxcelerate\Tests\Feature;

use malpaso\LaravelAxcelerate\Http\Client;
use malpaso\LaravelAxcelerate\LaravelAxcelerate;
use malpaso\LaravelAxcelerate\Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('axcelerate', [
            'base_url' => 'https://example.axcelerate.com',
            'ws_token' => 'test-ws-token',
            'api_token' => 'test-api-token',
        ]);
    }

    public function test_client_is_bound_as_singleton(): void
    {
        $client1 = $this->app->make(Client::class);
        $client2 = $this->app->make(Client::class);

        $this->assertSame($client1, $client2);
        $this->assertInstanceOf(Client::class, $client1);
    }

    public function test_laravel_axcelerate_is_bound_as_singleton(): void
    {
        $axcelerate1 = $this->app->make(LaravelAxcelerate::class);
        $axcelerate2 = $this->app->make(LaravelAxcelerate::class);

        $this->assertSame($axcelerate1, $axcelerate2);
        $this->assertInstanceOf(LaravelAxcelerate::class, $axcelerate1);
    }

    public function test_laravel_axcelerate_receives_client_dependency(): void
    {
        $axcelerate = $this->app->make(LaravelAxcelerate::class);
        $client = $this->app->make(Client::class);

        $this->assertSame($client, $axcelerate->getClient());
    }
}
