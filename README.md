# laravel-graphql

## Query

```php

use Jdefez\LaravelGraphql\QueryBuilder\Builder;
use Jdefez\LaravelGraphql\Facades;

// simple query

$query = Builder::query()
  ->user(
      ['id' => 1],
      fn (Builder $user) => $user
          ->email()
          ->name()
          ->id()
  );
```

```
query {
  user(id: 1) {
    email
    name
    id
  }
}
```

```php
$response = Graphql::request('api/url')
  ->setToken('my-token')
  ->get($query)
  ->toObject();
```

```json
"query": {
  "data": {
    "user": {
      "email": "h.green@mailer.com",
      "name": "Hank Green",
      "id": 1
    }
  }
}
```

## Mutation

```php
$query = Builder::mutation([
    '$name' => 'String!',
    '$email' => 'String!'
])->createUser(
    [
      'name' => '$name',
      'email' => '$email'
    ],
    fn (Builder $user) => $user
          ->name()
          ->email()
);

```
mutation($name: String!, $email: String!) {
  createUser(name: $name, email: $email) {
    name
    email
   }
}
```

```php
$response = Graphql::request('api/url')
  ->setToken('my-token')
  ->post($query, [
    'variables' => [
      'name' => 'hank Green',
      'email' => 'h.green@mailer.com'
    ]
  ])
  ->toObject();
```

```json
"mutation": {
  "data": {
    "createUser": {
      "id": 1,
      "name": "Hank Green",
      "email": "h.green@mail.com"
    }
  }
}
```
