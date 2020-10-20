## Setup

```
$ git clone https://github.com/evthedev/visibility-calculator.git
$ cd visibility-calculator
$ composer install
$ cp .env.example .env
$ php artisan key:generate
$ php artisan serve
$ phpunit
```

## Assumptions made
* There will be only one ranking for a specific term and engine on a specific date
* Search terms of different cases will be treated as unique terms
* No special handling of extra large files included, so depending on the import file size and php memory limit, the operation may timeout.
