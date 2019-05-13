# Description

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lbhurtado/missive.svg?style=flat-square)](https://packagist.org/packages/lbhurtado/missive)
[![Build Status](https://img.shields.io/travis/lbhurtado/missive/master.svg?style=flat-square)](https://travis-ci.org/lbhurtado/missive)
[![Quality Score](https://img.shields.io/scrutinizer/g/lbhurtado/missive.svg?style=flat-square)](https://scrutinizer-ci.com/g/lbhurtado/missive)
[![Total Downloads](https://img.shields.io/packagist/dt/lbhurtado/missive.svg?style=flat-square)](https://packagist.org/packages/lbhurtado/missive)

Add SMS domain to a Laravel project - models, migrations, jobs, notifications, etc.

## Installation

You can install the package via composer:

```bash
composer require lbhurtado/missive
```

```bash
php artisan vendor:publish --provider="LBHurtado\Missive\MissiveServiceProvider"
php artisan migrate
```

## Usage

``` php
use LBHurtado\Missive\Models\SMS;
use LBHurtado\Missive\Jobs\CreateSMS;
use LBHurtado\Missive\Repositories\SMSRepository;

CreateSMS::dispatch($attributes);
$sms = SMS::first();

$smss = app(SMSRepository::class);
$sms = $smss->first();
```

``` php$
use LBHurtado\Missive\Models\Contact;
use LBHurtado\Missive\Jobs\CreateContact;
use LBHurtado\Missive\Repositories\ContactRepository;

CreateContact::dispatch($mobile);
$contact = Contact::first();

$contacts = app(ContactRepository::class);
$contact = $contacts->first();
```

``` php
use LBHurtado\Missive\Models\Relay;
use LBHurtado\Missive\Jobs\CreateRelay;
use LBHurtado\Missive\Repositories\RelayRepository;

CreateRelay::dispatch($mobile);
$relay = Relay::first();

$relays = app(RelayRepository::class);
$relay = $relays->first();
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email lester@hurtado.ph instead of using the issue tracker.

## Credits

- [Lester Hurtado](https://github.com/lbhurtado)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
