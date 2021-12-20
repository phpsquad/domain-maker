# domain-maker

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

## Install
```bash
composer require phpsquad/domain-maker
```

## Usage
The package makes Domain Driven Development easier in Laravel. 

### All Domain Maker Commands are under the prefix domain.

```bash
 domain:make:controller        Create a new controller class
 domain:make:domain            Create a new Domain
 domain:make:routes            Create a new routes for domain
 ...
```

### Create new Domain
```bash
php artisan domain:make:domain
```
If this is the first domain the Domains directory will be created under app/Domains along with the specified domain. 

```Bash
Domains
├── Invoice
│   ├── Http
│   │   ├── Controllers
│   │   │   └── InvoiceController.php
│   │   ├── Middleware
│   │   └── Requests
│   ├── Models
│   ├── Repositories
│   └── routes
│       └── Invoice.php
└── Media
    ├── Http
    │   ├── Controllers
    │   │   └── MediaController.php
    │   ├── Middleware
    │   └── Requests
    ├── Models
    ├── Repositories
    └── routes
        └── Media.php

```


### Routing
A standard route file is created when you create a domain via the command. 
>Routes are discovered automatically via the DomainRouteServiceProvider

To create subsequent route files use:

```bash
domain:make:routes  <domain-name> <route-file-name>
```
For example, if I have a "Payments" domain, and I'd like to group my Stripe Routes I'd run the command like so:

```bash
domain:make:routes Payments Stripe
```


## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Richard Rohrig](https://github.com/phpsquad)
- [All Contributors](https://github.com/phpsquad/domain-maker/contributors)

## Security
If you discover any security-related issues, please email rick@wambo.com instead of using the issue tracker.

## License
The MIT License (MIT). Please see [License File](/LICENSE.md) for more information.
