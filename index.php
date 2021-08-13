<?php

require 'vendor/autoload.php';

use Jdefez\Graphql\Field;
use Jdefez\Graphql\QueryBuilder;

echo QueryBuilder::query()
    ->user(['id' => 1], function (Field $field) {
        $field->id()
            ->name()
            ->email();
    })
    ->addresses(function (Field $field) {
        $field->data(function(Field $field) {
            $field->street()
                ->zip()
                ->city();
        });
    });
