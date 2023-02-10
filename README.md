# Filament Curso

Con la base de FilamentExtendedStarterKit is a [Filament](https://filamentphp.com/) distribution with lots
of basic utilities and goodies pre-installed.

## Installation

Install dependencies

```bash
composer update
```

Run migrations

```bash
php artisan migrate
```

Create the first/admin user:

```
php artisan make:filament-user
```

Init FilamentShield

```
php artisan shield:install
```

For the FilamentShield install, answer "yes" to all questions it asks.

In theory, that should be it. You can now go to /admin on your site and you should see the filament
login screen. Log in with the user you created in step #4 above.
