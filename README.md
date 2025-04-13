### Requirements
Requires `composer` `php8.3`, `php-xml`, `sqlite3`


### Installation
Run `composer install`, confirm migrations prompt

Serve application with `php artisan serve`

API access:

/api/encode - accepts url query parameter

/api/decode - accepts url query parameter

/api/index - returns all url records

### Testing
Run tests with `php artisan test`

**Test Files:** 

[UrlsModelTest.php](tests/Feature/UrlsModelTest.php)

[UrlEncoderControllerTest.php](tests/Feature/UrlEncoderControllerTest.php)

[UrlsUnitTest.php](tests/Unit/UrlsUnitTest.php)
