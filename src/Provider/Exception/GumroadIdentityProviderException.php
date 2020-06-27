<?php

/*
 * Gumroad OAuth2 Provider
 * (c) alofoxx
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alofoxx\OAuth2\Client\Provider\Exception;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Psr\Http\Message\ResponseInterface;

/**
 * GumroadIdentityProviderException.
 *
 * @author alofoxx <alofoxx@gmail.com>
 */
class GumroadIdentityProviderException extends IdentityProviderException
{
    /**
     * Creates client exception from response.
     *
     * @param ResponseInterface $response Response received from upstream
     * @param mixed $data Parsed response data
     * @return IdentityProviderException
     */
    public static function clientException(ResponseInterface $response, $data)
    {
        return static::fromResponse(
            $response,
            isset($data['message']) ? $data['message'] : $response->getReasonPhrase()
        );
    }

    /**
     * Creates oauth exception from response.
     *
     * @param ResponseInterface $response Response received from upstream
     * @param string $data                Parsed response data
     * @return IdentityProviderException
     */
    public static function oauthException(ResponseInterface $response, $data)
    {
        return static::fromResponse(
            $response,
            isset($data['error']) ? $data['error'] : $response->getReasonPhrase()
        );
    }

    /**
     * Creates identity exception from response.
     *
     * @param ResponseInterface $response Response received from upstream
     * @param string|null $message        Parsed message
     * @return IdentityProviderException
     */
    protected static function fromResponse(ResponseInterface $response, $message = null)
    {
        return new static($message, $response->getStatusCode(), (string) $response->getBody());
    }
}
