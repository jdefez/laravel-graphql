<?php

require 'vendor/autoload.php';

use Jdefez\Graphql\Field;
use Jdefez\Graphql\QueryBuilder;

//$query = QueryBuilder::query()
    //->user(['id' => 1], function(Field $field) {
       //$field->id()
       //$field->name()
       //$field->email()
    //})
    //->bar()

//echo $query;

$query = QueryBuilder::query()
    ->addField(Field::setName('user')
        ->setArguments(['id' => 1])
        ->addFields([
            Field::setName('id'),
            Field::setName('name'),
            Field::setName('email')
        ]))
    ->addField(new Field('bar'));

echo $query;
