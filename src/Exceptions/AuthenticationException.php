<?php

namespace malpaso\LaravelAxcelerate\Exceptions;

class AuthenticationException extends AxcelerateException
{
    public static function invalidTokens(): self
    {
        return new self('Invalid API tokens provided. Check your wstoken and apitoken configuration.');
    }

    public static function missingTokens(): self
    {
        return new self('API tokens are required. Please configure wstoken and apitoken.');
    }
}
