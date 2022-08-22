

# Quickstart wrapper for Laravel using Laravel Socialite.

[![Version](https://img.shields.io/packagist/v/chrisjk123/laravel-social.svg?include_prereleases&style=flat&label=packagist)](https://packagist.org/packages/chrisjk123/laravel-social)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE.md)
![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/chrisjk123/laravel-social/run-tests?style=flat&label=tests)

This package serves as a quick, helpful wrapper around Laravel Socialite.

**NOTE**: all OAuth authentication logins made by [Laravel Socialite](https://github.com/laravel/socialite) are supported.

## Installation

You can install the package via composer:

```bash
composer require chrisjk123/laravel-social
```

You can optionally publish the config file with:

```bash
php artisan vendor:publish --provider="Chriscreates\Social\Providers\SocialServiceProvider" --tag="social-config"
```

Be sure to update `config\services.php` following [Laravel Socialite's documentation](https://laravel.com/docs/socialite).


```php
<?php
  
return [
    // as an example:
    'google' => [
	    'client_id' => env('GOOGLE_CLIENT_ID'),
	    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
	    'redirect' => env('GOOGLE_REDIRECT'),
    ],
    
];
```

Make sure your `use Illuminate\Foundation\Auth\User as Authenticatable;` model is passwordless i.e. the password field is nullable.

## Usage

To login with a given provider, simply pass in the provider name and have the user visit the route:

```php
route('auth.provider.callback',  ['provider'  =>  'google'])
```

## Customization

The auth validation and creation process may be customized via "Action" classes. Be sure to review this repository's source code and read up on the [Laravel Fortify](https://laravel.com/docs/fortify) docs as an example.

**Contracts**:
`Chriscreates\Social\Contracts\SocialCreateAuthContract`
`Chriscreates\Social\Contracts\SocialFindAuthContract`

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email christopherjk123@gmail.com instead of using the issue tracker.

## Credits

- [Christopher Kelker](https://github.com/chrisjk123)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
