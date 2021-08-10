<?php

require 'vendor/autoload.php';

use Jdefez\Graphql\Field;
use Jdefez\Graphql\QueryBuilder;

$query = QueryBuilder::query()
    ->addField(Field::setName('trunc')
        //todo ->setArguments([])
        ->addFields([
            Field::setName('leaf 1'),
                //todo ->setArguments([])
            Field::setName('leaf 2')
                ->addField(Field::setName('bee')),
            Field::setName('leaf 3')
        ])
    );

echo $query;
