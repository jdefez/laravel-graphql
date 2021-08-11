<?php

namespace Jdefez\Graphql;

class QueryBuilder extends Node
{
    public static function query(): QueryBuilder
    {
        return new self();
    }

    public function toString(): string
    {
        $return = 'QUERY {' . PHP_EOL;
        foreach ($this->fields as $field) {
            $return .= $field->toString();
        }
        $return .= '}';

        return $return;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
