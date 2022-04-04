# Domain Maker for Laravel

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

## The Why

Domain Driven Design helps us to organize our thoughts and to build apps using logical grouping of our code.

If you've ever worked on a large laravel project you know how that model directory can grow so large that your ability to find things becomes hampered.

I was inspired by this article https://freek.dev/1486-getting-started-with-domain-oriented-laravel from Freek at Spatie to refactor to Domains.
I love it! It makes it so much easier to focus on a specific issue without the need to traverse the entire code base.
If I'm working on Payments, I live in the payments Domain.

I soon realized refactoring to DDD is pretty straight forward, but the typically wonderful development experience I've grown used to with laravel
left a bit to be desired.

Also, what if I know my project is going to be large, and I want to get a head start and begin development using DDD?

That's why this package exists.

## How can Domain Maker help you?

Domain Maker makes Domain Driven Development easier in Laravel by providing you with a set of commands to create the scaffolding and boilerplate
laravel normally provides but tailored to a Domain Oriented Structure.

- Helpful Commands to:
  - Automatically scaffold a new Domain with the often needed directories and classes
  - create controllers
  - create route files
  - create models
  - create repositories
- Automatic Routes discovery (no need to register routes in the RouteServiceProvider)
- Automatic View discovery (no need to add view path to config)

### All Domain Maker Commands are under the prefix domain.

```bash
 domain:make:controller        Create a new controller class
 domain:make:domain            Create a new Domain
 domain:make:routes            Create a new routes for domain
 ...
```

## Install

```bash
composer require phpsquad/domain-maker
```

## Usage

### Create new Domain

```bash
php artisan domain:make:domain
```

If this is the first domain the Domains directory will be created under app/Domains along with the specified domain.

```Bash
Domains
└── Media
    ├── Exceptions
    ├── Http
    │   ├── Controllers
    │   │   ├── VimeoController.php
    │   │   └── YoutubeController.php
    │   ├── Middleware
    │   └── Requests
    │       └── YoutubeRequest.php
    ├── Jobs
    │   └── YoutubeSync.php
    ├── Models
    │   └── Youtube.php
    ├── Repositories
    │   └── YoutubeRepository.php
    ├── resources
    │   ├── css
    │   ├── js
    │   └── views
    │       └── youtube-home.blade.php
    ├── routes
    │   ├── Media.php
    │   ├── Vimeo.php
    │   └── Youtube.php
    └── Services


```

### Routing

A standard route file is created when you create a domain via the `domain:make:domain` command.

> Routes are discovered automatically via the DomainRouteServiceProvider

To create subsequent route files use:

```bash
domain:make:routes  <domain-name> <route-file-name>
```

For example, if I have a "Payments" domain, and I'd like to group my Stripe Routes I'd run the command like so:

```bash
domain:make:routes Payments Stripe
```

### Repositories

A repository with standard CRUDs can easily be generate by using the `domain:make:repository`.

```bash
domain:make:repository <domain-name> <repository-name> <model-name>
```

Using the "Payments" domain again to demonstrate, we can use the command as follows:

```bash
domain:make:repository Payments PaymentRepository Payment
```

### Stubs

Public stubs will be used as a default. If stubs are unpublished, backups are contained in the package.

There are package specific stubs that you may publish to override (i.e., routes.stub)

> If you don't need to make changes to the stubs it's not necessary to publish them.

```bash
php artisan vendor:publish --tag=domain-stubs
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Richard Rohrig](https://github.com/phpsquad)
- [All Contributors](https://github.com/phpsquad/domain-maker/contributors)

## Security

If you discover any security-related issues, please email richard.t.rohrig@gmail.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](/LICENSE.md) for more information.
