# laravel-graphql

Small utility class to store Graphql queries and mutations as php classes

## Queries

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

echo $query->dump();

// outputs
//
// query {
//   user(id: 1) {
//     email
//     name
//     id
//   }
// }
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

## Using partial query

It's about refactoring a portion of query in another class.
Graphql queries can be large and we may want to store some reusable parts of
those queries.

```php
class AddressBuilder extends Builder
{
    public static function make(?array $arguments = null): static
    {
        return Builder::make($arguments)
            ->zipcode()
            ->street()
            ->city()
    }
}

$query = Builder::query()
    ->user(
        ['id' => 1],
        fn (Builder $user) => $user
            ->email()
            ->name()
            ->id()
    )->addresses(
        AddressBuilder::make(['trashed' => 'WITH'])
    );

echo $query->dump();

// outputs
//
// query {
//   user(id: 1) {
//     email
//     name
//     id
//   } addresses(trashed: WITH) {
//     street
//     city
//     zipcode
//   }
// }
```

## Mutations

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

echo $query->dump();

// outputs
//
// mutation($name: String!, $email: String!) {
//   createUser(name: $name, email: $email) {
//     name
//     email
//    }
// }

```

```php
$response = Graphql::request('api/url')
  ->setToken('my-token')
  ->post($query, [
    'variables' => [
      'name' => 'hank Green',
      'email' => 'h.green@mailer.com'
    ]
  ])->toObject();
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
