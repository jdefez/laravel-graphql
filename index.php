<?php

require 'vendor/autoload.php';

use Jdefez\Graphql\Field;
use Jdefez\Graphql\QueryBuilder;

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
