<?php

require 'vendor/autoload.php';

use Jdefez\Graphql\QueryBuilder;
use Jdefez\Graphql\Field;

$query = QueryBuilder::query()
    ->user(['id' => 1], function (Field $user) {
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
