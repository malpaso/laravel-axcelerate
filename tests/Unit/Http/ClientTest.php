<?php

namespace malpaso\LaravelAxcelerate\Tests\Unit\Http;

use malpaso\LaravelAxcelerate\Exceptions\AuthenticationException;
use malpaso\LaravelAxcelerate\Http\Client;
use malpaso\LaravelAxcelerate\Tests\TestCase;

class ClientTest extends TestCase
{
    public function test_it_throws_exception_when_base_url_missing(): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Base URL is required');

        new Client([
            'ws_token' => 'test-ws-token',
            'api_token' => 'test-api-token',
        ]);
    }

    public function test_it_throws_exception_when_ws_token_missing(): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('API tokens are required');

        new Client([
            'base_url' => 'https://example.axcelerate.com',
            'api_token' => 'test-api-token',
        ]);
    }

    public function test_it_throws_exception_when_api_token_missing(): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('API tokens are required');

        new Client([
            'base_url' => 'https://example.axcelerate.com',
            'ws_token' => 'test-ws-token',
        ]);
    }

    public function test_it_can_be_instantiated_with_valid_config(): void
    {
        $config = [
            'base_url' => 'https://example.axcelerate.com',
            'ws_token' => 'test-ws-token',
            'api_token' => 'test-api-token',
        ];

        $client = new Client($config);

        $this->assertInstanceOf(Client::class, $client);
    }
}
