# Form Maker plugin for FilamentPHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/afsakar/filament-form-maker.svg?style=flat-square)](https://packagist.org/packages/afsakar/filament-form-maker)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/afsakar/filament-form-maker/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/afsakar/filament-form-maker/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/afsakar/filament-form-maker/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/afsakar/filament-form-maker/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/afsakar/filament-form-maker.svg?style=flat-square)](https://packagist.org/packages/afsakar/filament-form-maker)

![Screenshot](https://banners.beyondco.de/Filament%20Form%20Maker.png?theme=light&packageManager=composer+require&packageName=afsakar%2Ffilament-form-maker&pattern=architect&style=style_1&description=Form+Maker+plugin+for+FilamentPHP&md=1&showWatermark=0&fontSize=100px&images=template)

Form Maker plugin for FilamentPHP, which allows you to create and manage forms easily.

## Installation

You can install the package via composer:

```bash
composer require afsakar/filament-form-maker
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-form-maker-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-form-maker-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-form-maker-views"
```

This is the contents of the published config file:

```php
return [
    'extra_collections' => [
        //  App\Models\User::class => 'User List',
    ],

    // TODO: Add extra fields feature
    'extra_fields' => [
        // 'field_name' => Field::class
    ],
];
```

## Usage

```php
// Usage 
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Azad Furkan ŞAKAR](https://github.com/afsakar)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
