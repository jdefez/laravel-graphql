<?php

namespace Jdefez\Graphql;

class Field extends Node
{
    public string $name;

    public array $arguments = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function setName(string $name): Field
    {
        return new self($name);
    }

    public function setArguments(array $arguments): Field
    {
        $this->arguments = $arguments;

        return $this;
    }

    public function addField(Field $field): Field
    {
        parent::addField($field);

        return $this;
    }

    public function addFields(array $fields): Field
    {
        parent::addFields($fields);

        return $this;
    }
}
