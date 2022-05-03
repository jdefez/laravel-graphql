# laravel-graphql

It consist of a set of Graphql utility classes to handle graphql request in a
laravel based project.

## The Builder class

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

Most of the time this class will guess which of the parameters should not be
quoted but constants and custom types can be difficult to detect. You can use a
`Unquoted` class to make sure your parameter is not quoted.

```php

use Jdefez\LaravelGraphql\QueryBuilder\Builder;
use Jdefez\LaravelGraphql\QueryBuilder\Unquoted;

$query = Builder::query()
    ->users(
        ['with' => new Unquoted('TRASHED')],
        fn (Builder $user) => $user
            ->firstname()
            ->lastname()
            ->email()
            ->id()
    );

echo (string) $query;

// => query { users(with: TRASHED}) { firstname lastname email id }}
```

## A GraphQL input class

Used along with your mutations when creating models Inputs. This class helps to
easily represent an input relations. `User.createAddresses` for example.

```php

use Jdefez\LaravelGraphql\QueryBuilder\Builder;

$query = Builder::mutation([$input => new Unquoted(UserInput!)])
    ->inserUser(
        ['input' => '$input'],
        fn (Builder $user) => $user
            ->firstname()
            ->lastname()
            ->email()
            ->id()
    );

$input = new UserInput(
    firstname: 'robert',
    lastname: 'smith',
    email: 'rsmith@webmail.com',
)->createAddresses(new AddressInput(
    street: '2345 main street'
    city: 'new york',
    zip: 'ny56789',
));

$response = new Client('api_url', 'token')->post($query, $input)->object();

dump($input->toArray())

// =>
// [
//    'firstname' => 'robert',
//    'lastname' => 'smith',
//    'email' => 'rsmith@webmail.com',
//    'addresses' => [
//        'create' => [
//            [
//                'street' => '2345 main street'
//                'city' => 'new york',
//                'zip' => 'ny56789',
//            ]
//        ]
//    ]
// ];
```

## A Client class

Nothing realy special here. It uses laravel Http facade. The returner response
is a `Illuminate\Http\Client\Response`. You can Refer to the [laravel
documentation](https://laravel.com/docs/9.x/http-client#making-requests) to see
how to handle the response.

```php

$response = new Client('api_url', 'token')->post($query, $input)->object();

```

