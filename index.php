<?php

require 'vendor/autoload.php';

use Jdefez\Graphql\QueryBuilder;
use Jdefez\Graphql\Field;

$query = QueryBuilder::query()
    ->user([
        'updated_at' => ['from' => '2021-08-01', 'to' => '2021-08-01'],
        'in' => [6, 7, 9],
        'trashed' => 'WITH'
    ], function (Field $user) {
        $user->id()
            ->name()
            ->email();
    })
    ->addresses(function (Field $addresses) {
        $addresses->data(function (Field $data) {
            $data->street()
                ->zip()
                ->city();
        });
    });

echo $query->dump();
