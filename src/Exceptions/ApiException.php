<?php

namespace malpaso\LaravelAxcelerate\Exceptions;

use Psr\Http\Message\ResponseInterface;

class ApiException extends AxcelerateException
{
    protected ?ResponseInterface $response = null;

    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null, ?ResponseInterface $response = null)
    {
        parent::__construct($message, $code, $previous);
        $this->response = $response;
    }

    public static function fromResponse(ResponseInterface $response, string $message = ''): self
    {
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();

        if (empty($message)) {
            $message = "API request failed with status {$statusCode}";

            // Try to extract error message from response body
            $decoded = json_decode($body, true);
            if (is_array($decoded) && isset($decoded['message'])) {
                $message .= ": {$decoded['message']}";
            }
        }

        return new self($message, $statusCode, null, $response);
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}
