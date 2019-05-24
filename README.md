# Missive

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lbhurtado/missive.svg?style=flat-square)](https://packagist.org/packages/lbhurtado/missive)
[![Build Status](https://img.shields.io/travis/lbhurtado/missive/master.svg?style=flat-square)](https://travis-ci.org/lbhurtado/missive)
[![Quality Score](https://img.shields.io/scrutinizer/g/lbhurtado/missive.svg?style=flat-square)](https://scrutinizer-ci.com/g/lbhurtado/missive)
[![Total Downloads](https://img.shields.io/packagist/dt/lbhurtado/missive.svg?style=flat-square)](https://packagist.org/packages/lbhurtado/missive)

Add SMS domain to a Laravel project - route, models, migrations, events, jobs, actions, etc.

## Installation

You can install the package via composer:

```bash
composer require lbhurtado/missive
```

publish vendor files:
```bash
php artisan vendor:publish --provider="LBHurtado\Missive\MissiveServiceProvider"
php artisan migrate


```
optionally, if you want sms charging:

```bash
composer dumpautoload
php artisan db:seed --class=AirtimeSeeder
```
then uncomment the middleware in missive.php

inherit models:
```php
namespace App;

use LBHurtado\EngageSpark\Traits\HasEngageSpark;
use LBHurtado\Missive\Models\Contact as BaseContact;

class Contact extends BaseContact
{
    use HasEngageSpark;
}
```
customize the tables and classes e.g. App\Contact:
```php
[
	'table_names' => [
		'smss'     => 's_m_s_s',
		'contacts' => 'contacts',
		'relays'   => 'relays'
	],
    'classes' => [
            'models' => [
                'contact' => \App\Contact::class,
                'relay' => \LBHurtado\Missive\Models\Relay::class,
                'sms' => \LBHurtado\Missive\Models\SMS::class
            ],
            'commands' => [
                'sms' => [
                    'create' => \LBHurtado\Missive\Commands\CreateSMSCommand::class
                ]
            ],
            'handlers' => [
                'sms' => [
                    'create' => \LBHurtado\Missive\Handlers\CreateSMSHandler::class
                ]
            ],
            'middlewares' => [
                'sms' => [
                    'relay' => [
                        \LBHurtado\Missive\Validators\CreateSMSValidator::class,
                        \LBHurtado\Missive\Responders\CreateSMSResponder::class,
    //                \LBHurtado\Missive\Actions\Middleware\ChargeSMSMiddleware::class,
                    ],
                    'verify' => [
                        \LBHurtado\Missive\Validators\CreateSMSValidator::class,
                        \LBHurtado\Missive\Responders\CreateSMSResponder::class,
                        \LBHurtado\Missive\Actions\Middleware\VerifyContactHandler::class,
    //                    \LBHurtado\Missive\Actions\Middleware\ChargeSMSMiddleware::class,
                    ],
                ],
            ]
        ]
]
```

customize the routes in routes/sms.php:
```php
use LBHurtado\EngageSpark\Notifications\Adhoc;

$router = resolve('missive:router');

$router->register('LOG {message}', function (string $path, array $values) use ($router) {
    \Log::info($values['message']);
    
    tap($router->missive->getSMS()->origin, function ($contact) use ($values) {
        $message = $values['message'];
        $contact->notify(new Adhoc("{$contact->mobile}: $message"));
    });
});
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

add otp verification to your contacts:
``` php
use LBHurtado\Missive\Traits\HasOTP;
use LBHurtado\Missive\Models\Contact;

$contact = Contact::find(1);
$otp = $contact->challenge()->now();

if ($contact->verify($otp) == true) {
    //code here
}
``` 

sms relay
``` bash
curl -X POST \
  http://laravel.app/api/sms/relay \
  -H 'Accept: */*' \
  -H 'Cache-Control: no-cache' \
  -H 'Connection: keep-alive' \
  -H 'Content-Type: application/json' \
  -H 'Host: laravel.app' \
  -d '{
    "secret": "CFAWG4KCE44XWACTZZX24Z7LPW99XTWT",
    "from": "+639171234567",
    "to": "+639187654321",
    "message": "LOG test message"
}'
```

sms OTP verification
``` bash
curl -X POST \
  http://laravel.app/api/sms/verify \
  -H 'Accept: */*' \
  -H 'Cache-Control: no-cache' \
  -H 'Connection: keep-alive' \
  -H 'Content-Type: application/json' \
  -H 'Host: laravel.app' \
  -d '{
    "secret": "CFAWG4KCE44XWACTZZX24Z7LPW99XTWT",
    "from": "+639171234567",
    "to": "+639187654321",
    "message": "123456"
}'
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
