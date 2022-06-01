<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Install Laravel API

- Copy .env.example to .env
- Edit APP_URL with API URL, edit MySQL config.
- Run command
  ```
  composer install
  php artisan migrate --seed
  php artisan passport:install
  ```
  
## Use multi auth
- Step 1: Add guards and providers to config/auth.php
- Step 2: Model use trait Laravel\Passport\HasApiTokens
- Step 3: Specify user provider uses --provider option

Example: provider customers

  ```
  php artisan passport:client --password --provider=customers
  ```

## Common

### 1. Constant

Instead of using const in class to import. We will use const in config/constants.php
Example:

```
    'TYPE_CUSTOMER' => 'customer',
    'TYPE_ADMIN' => 'admin',
    'TYPE_MANAGER' => 'manager',
```

### 2. Rule commit

## Rule Commit Message

Based on the Angular convention:

```
type(scope?): subject
```

- build: Changes that affect the build system or external dependencies (example scopes: gulp, broccoli, npm)
- ci: Changes to our CI configuration files and scripts (example scopes: Gitlab CI, Circle, BrowserStack, SauceLabs)
- chore: add something without touching production code (Eg: update npm dependencies)
- docs: Documentation only changes
- feat: A new feature
- fix: A bug fix
- perf: A code change that improves performance
- refactor: A code change that neither fixes a bug nor adds a feature
- revert: Reverts a previous commit
- style: Changes that do not affect the meaning of the code (Eg: adding white-space, formatting, missing semi-colons,
  etc)
- test: Adding missing tests or correcting existing tests

## Package Intergrate

### Laravel Debugbar

https://github.com/barryvdh/laravel-debugbar

### Laravel IDE Helper Generator

https://github.com/barryvdh/laravel-ide-helper

### Passport (use Password Grant Tokens):

https://laravel.com/docs/8.x/passport

### Laravel Permission:

https://spatie.be/docs/laravel-permission/v5/basic-usage/basic-usage

### Generate doc api (Swagger):

https://github.com/DarkaOnLine/L5-Swagger

1. Add comments to controller's method

```

/**
     * @OA\Post(
     *  path="/auth/login",
     *  operationId="registerUser",
     *  tags={"User"},
     *  summary="Register user",
     *  description="Returns message and status",
     *  @OA\Parameter(name="name",
     *    in="query",
     *    required=true,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(name="email",
     *    in="query",
     *    required=true,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(name="password",
     *    in="query",
     *    required=true,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Response(response="201",
     *    description="Register success",
     *  )
     * )
     */
```

Reference: https://blog.quickadminpanel.com/laravel-api-documentation-with-openapiswagger/

2. Run

```
   php artisan l5-swagger:generate
   sudo chmod 777 -R storage/api-docs/
```

3. View document api
   You can access your documentation at `/api/documentation` endpoint

### Laravel Excel

Read More: https://docs.laravel-excel.com/3.1/getting-started/
