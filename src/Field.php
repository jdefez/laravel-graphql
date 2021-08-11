<?php

namespace Jdefez\Graphql;

class Field extends Node
{
    public static function setName(string $name): Node
    {
        return new self($name);
    }

    public function __call(string $name, ?array $arguments = null): Node
    {
        $field = new Field($name, $arguments);
        $this->addField($field);

        return $this;
    }
}
