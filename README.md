# Gumroad Provider for OAuth 2.0 Client
[![Source Code](http://img.shields.io/badge/source-alofoxx/oauth2--gumroad-blue.svg?style=flat-square)](https://github.com/alofoxx/oauth2-gumroad)
[![Latest Version](https://img.shields.io/github/release/alofoxx/oauth2-gumroad.svg?style=flat-square)](https://github.com/alofoxx/oauth2-gumroad/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/alofoxx/oauth2-gumroad/master.svg?style=flat-square)](https://travis-ci.org/alofoxx/oauth2-gumroad)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Alofoxx/oauth2-gumroad/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Alofoxx/oauth2-gumroad/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/Alofoxx/oauth2-gumroad/badge.svg?branch=master)](https://coveralls.io/github/Alofoxx/oauth2-gumroad?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/alofoxx/oauth2-gumroad.svg?style=flat-square)](https://packagist.org/packages/alofoxx/oauth2-gumroad)

This package provides Gumroad OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client), v2.0 and up.

## Requirements

The following versions of PHP are supported.

* PHP 7.1
* PHP 7.2
* PHP 7.3
* PHP 7.4

## Installation

To install, use composer:

```bash
$ composer require alofoxx/oauth2-gumroad
```

## Usage

Usage is the same as The League's OAuth client, using `\Alofoxx\OAuth2\Client\Provider\Gumroad` as the provider.

### Sample Authorization Code Flow

This self-contained example:

1. Gets an authorization code
1. Gets an access token using the provided authorization code
1. Looks up the user's profile with the provided access token

You can try this script by [registering a Gumroad Application](https://gumroad.com/settings/advanced#application-form) with a redirect URI to your server's copy of this sample script. Then, place the Gumroad app's application id and secret, along with that same URI, into the settings at the top of the script.

```php
<?php

require __DIR__ . '/vendor/autoload.php';

session_start();

echo ('Main screen turn on!<br/><br/>');

$provider = new \Alofoxx\OAuth2\Client\Provider\Gumroad([
    'clientId' => '{gumroad-application-id}',
    'clientSecret' => '{gumroad-application-secret}',
    'redirectUri' => '{your-server-uri-to-this-script-here}'
]);

if (!isset($_GET['code'])) {

    // Step 1. Get authorization code
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: ' . $authUrl);

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Step 2. Get an access token using the provided authorization code
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Show some token details
    echo '<h2>Token details:</h2>';
    echo 'Token: ' . $token->getToken() . "<br/>";
    echo 'Refresh token: ' . $token->getRefreshToken() . "<br/>";
    echo 'Expires: ' . $token->getExpires() . " - ";
    echo ($token->hasExpired() ? 'expired' : 'not expired') . "<br/>";

    // Step 3. (Optional) Look up the user's profile with the provided token
    try {

        $user = $provider->getResourceOwner($token);

        echo '<h2>Resource owner details:</h2>';
        printf('Hello %s!<br/><br/>', $user->getName());
        var_export($user->toArray());

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');

    }
}
```

### Managing Scopes

When creating your Gumroad authorization URL in Step 1, you can specify the state and scopes your application may authorize.

```php
$options = [
    'state' => 'OPTIONAL_CUSTOM_CONFIGURED_STATE',
    'scope' => ['edit_products', 'view_sales', 'mark_sales_as_shipped'] // array or string
];

$authorizationUrl = $provider->getAuthorizationUrl($options);
```
If neither are defined, the provider will utilize internal default of `view_sales`.

At the time of authoring this documentation, the [following scopes are available](https://gumroad.com/api#api-scopes):

- ` `  (empty string for no scope)
- `edit_products`
- `view_sales` 
- `mark_sales_as_shipped`
- `refund_sales`

### Refreshing a Token

You can refresh an expired token using a refresh token rather than going through the entire process of obtaining a brand new token. To do so, simply reuse the fresh token from your data store to request a refresh:

```php
// create $provider as in the initial example
$existingAccessToken = getAccessTokenFromYourDataStore();

if ($existingAccessToken->hasExpired()) {
    $newAccessToken = $provider->getAccessToken('refresh_token', [
        'refresh_token' => $existingAccessToken->getRefreshToken()
    ]);

    // Purge old access token and store new access token to your data store.
}
```

## Testing

``` bash
$ ./vendor/bin/parallel-lint src test
$ ./vendor/bin/phpcs src --standard=psr2 -sp
$ ./vendor/bin/phpunit --coverage-text
```

## Contributing

Please see [CONTRIBUTING](https://github.com/alofoxx/oauth2-gumroad/blob/master/CONTRIBUTING.md) for details.

## Credits

- [Alofoxx](https://github.com/alofoxx)
- [All Contributors](https://github.com/alofoxx/oauth2-gumroad/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/alofoxx/oauth2-gumroad/blob/master/LICENSE) for more information.
