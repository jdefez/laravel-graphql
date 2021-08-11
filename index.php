<?php

require 'vendor/autoload.php';

use Jdefez\Graphql\Field;
use Jdefez\Graphql\QueryBuilder;

$query = QueryBuilder::query()
    ->user(['id' => 1], function (Field $field) {
        $field->id()
            ->name()
            ->email();
        // todo: test children
    })
    ->address(function (Field $field) {
        $field->street()
            ->zip()
            ->city();
        // todo: test children
    });

echo $query;
