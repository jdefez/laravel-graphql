# laravel-graphql

It consist of a set of Graphql utility classes to handle graphql request in a
laravel based project.

## A GraphQL query builder

The purpose of this class is to build GraphQL queries through reusable php
classes.file:///home/jean/Code/laravel-graphql/build/coverage/Graphql.php.html

```php

use Jdefez\LaravelGraphql\QueryBuilder\Builder;

$query = Builder::query()
    ->users(fn (Builder $user) => $user
            ->email()
            ->name()
            ->id()
    );

echo (string) $query;

// => query { users { email name id }}
```

This class makes use of the php *__call* magic method to represent the fields
of a GraphQL API. If the user endpoint had the fields `firstname` and
`lastname` you could represent them like this:

```php
  (...)
  fn (Builder $user) => $user
      ->firstname()
      ->lastname()
  (...)

echo (string) $query;

// => query { users { email firstname lastname id }}
```

You can also query GraphQL API model relations by chaining builders.

```php

use Jdefez\LaravelGraphql\QueryBuilder\Builder;

$query = Builder::query()
    ->users(fn (Builder $user) => $user
            ->email()
            ->name()
            ->id()
            ->addresses(fn (Builder $address) => $address
                ->street()
                ->zip()
                ->city()
            )
    );

echo (string) $query;

// => query { users { email name id addresses { street zip city } }}

```

This Builder class also handles query parameters.

```php

use Jdefez\LaravelGraphql\QueryBuilder\Builder;

$query = Builder::query()
    ->users(
        ['filter' => ['firstname' => ['eq' => 'bob']]],
        fn (Builder $user) => $user
            ->firstname()
            ->lastname()
            ->email()
            ->id()
    );

echo (string) $query;

// => query { users(filter: {firstname: {eq: "bob"}}) { firstname lastname email id }}
```

todo: [!] use of Unquoted class to render arguments that should not be
surrounded with quotes.

## A GraphQL input class

Used along with your queries when creating models for example.

## A Client


