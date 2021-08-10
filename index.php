<?php

require 'vendor/autoload.php';

use Jdefez\Graphql\Field;
use Jdefez\Graphql\QueryBuilder;

$query = QueryBuilder::query()
    ->addField(Field::setName('trunc')
        ->addFields([
            Field::setName('leaf 1'),
            Field::setName('leaf 2')
                ->addField(Field::setName('bee')),
            Field::setName('leaf 3')
        ])
    );

//die(var_dump($query));

echo $query;
