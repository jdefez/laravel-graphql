<?php

namespace Jdefez\Graphql;

class QueryBuilder extends Node
{
    public Query $query;

    public function __construct()
    {
        $this->addField(new Field('query'));
    }

    public static function query(): QueryBuilder
    {
        return new self();
    }

    public function addField(Field $field): QueryBuilder
    {
        parent::addField($field);

        return $this;
    }

    public function addFields(array $fields): QueryBuilder
    {
        parent::addFields($fields);

        return $this;
    }

    public function toString(): string
    {
        $return = '';
        if ($this->hasFields()) {
            foreach($this->fields as $field) {
                $return .= $field->toString();
            }
        }
        return $return;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
