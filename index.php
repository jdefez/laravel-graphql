<?php

require 'vendor/autoload.php';

use Jdefez\Graphql\Field;
use Jdefez\Graphql\QueryBuilder;

$query = QueryBuilder::query()
    ->addField(Field::setName('user')
        ->setArguments(['id' => 1])
        ->addFields([
            Field::setName('id'),
            Field::setName('name'),
            Field::setName('email')
        ])
    );

echo $query;
