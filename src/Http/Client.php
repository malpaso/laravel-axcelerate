<?php

namespace malpaso\LaravelAxcelerate\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Log;
use malpaso\LaravelAxcelerate\Exceptions\ApiException;
use malpaso\LaravelAxcelerate\Exceptions\AuthenticationException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client
{
    protected GuzzleClient $client;

    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->validateConfig();
        $this->client = $this->createHttpClient();
    }

    protected function validateConfig(): void
    {
        if (empty($this->config['base_url'])) {
            throw new AuthenticationException('Base URL is required');
        }

        if (empty($this->config['ws_token']) || empty($this->config['api_token'])) {
            throw AuthenticationException::missingTokens();
        }
    }

    protected function createHttpClient(): GuzzleClient
    {
        $stack = HandlerStack::create();

        // Add logging middleware if enabled
        if ($this->config['log_requests'] ?? false) {
            $stack->push(Middleware::mapRequest(function (RequestInterface $request) {
                Log::info('Axcelerate API Request', [
                    'method' => $request->getMethod(),
                    'uri' => (string) $request->getUri(),
                    'headers' => $request->getHeaders(),
                ]);

                return $request;
            }));

            $stack->push(Middleware::mapResponse(function (ResponseInterface $response) {
                Log::info('Axcelerate API Response', [
                    'status' => $response->getStatusCode(),
                    'headers' => $response->getHeaders(),
                ]);

                return $response;
            }));
        }

        // Add retry middleware
        $stack->push(Middleware::retry(
            function ($retries, RequestInterface $request, ?ResponseInterface $response = null, ?RequestException $exception = null) {
                if ($retries >= ($this->config['retry_attempts'] ?? 3)) {
                    return false;
                }

                if ($exception && $exception->getCode() >= 500) {
                    return true;
                }

                if ($response && $response->getStatusCode() >= 500) {
                    return true;
                }

                return false;
            },
            function ($numberOfRetries) {
                return ($this->config['retry_delay'] ?? 1000) * $numberOfRetries;
            }
        ));

        return new GuzzleClient([
            'base_uri' => rtrim($this->config['base_url'], '/').'/api/',
            'timeout' => $this->config['timeout'] ?? 30,
            'handler' => $stack,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'wstoken' => $this->config['ws_token'],
                'apitoken' => $this->config['api_token'],
            ],
        ]);
    }

    public function get(string $endpoint, array $query = []): array
    {
        return $this->request('GET', $endpoint, ['query' => $query]);
    }

    public function post(string $endpoint, array $data = []): array
    {
        return $this->request('POST', $endpoint, ['json' => $data]);
    }

    public function put(string $endpoint, array $data = []): array
    {
        return $this->request('PUT', $endpoint, ['json' => $data]);
    }

    public function delete(string $endpoint): array
    {
        return $this->request('DELETE', $endpoint);
    }

    protected function request(string $method, string $endpoint, array $options = []): array
    {
        try {
            $response = $this->client->request($method, $endpoint, $options);

            return $this->handleResponse($response);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();

                if ($response->getStatusCode() === 401 || $response->getStatusCode() === 403) {
                    throw AuthenticationException::invalidTokens();
                }

                throw ApiException::fromResponse($response);
            }

            throw new ApiException("Request failed: {$e->getMessage()}", $e->getCode(), $e);
        } catch (GuzzleException $e) {
            throw new ApiException("HTTP client error: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    protected function handleResponse(ResponseInterface $response): array
    {
        $body = $response->getBody()->getContents();

        if ($response->getStatusCode() >= 400) {
            throw ApiException::fromResponse($response);
        }

        $decoded = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException('Invalid JSON response from API');
        }

        return $decoded ?? [];
    }
}
