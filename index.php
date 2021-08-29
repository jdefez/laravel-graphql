<?php

require 'vendor/autoload.php';

use Jdefez\LaravelGraphql\QueryBuilder\Builder;

$query = Builder::query()
    ->user([
        'updated_at' => ['from' => '2021-08-01', 'to' => '2021-08-01'],
        'in' => [6, 7, 9],
        'trashed' => '$WITH'
    ], function (Builder $user) {
        $user->id()
            ->name()
            ->email();
    })
    ->addresses(function (Builder $addresses) {
        $addresses->data(function (Builder $data) {
            $data->street()
                ->zip()
                ->city();
        });
    });

echo $query->dump();
