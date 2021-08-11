<?php

namespace Jdefez\Graphql;

class QueryBuilder extends Node
{
    public static function query(): QueryBuilder
    {
        return new self();
    }

    public function __call(string $name, ?array $arguments = null): QueryBuilder
    {
        $callback = null;
        if ($arguments
            && is_callable($arguments[count($arguments) - 1])
        ) {
            $callback = array_pop($arguments);
            $arguments = $arguments[0];
        }

        $field = new Field($name, $arguments);
        $this->addField($field);

        if ($callback) {
            $callback($field);
        }

        return $this;
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
