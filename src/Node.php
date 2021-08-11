<?php

namespace Jdefez\Graphql;

class Node
{
    public string $name;

    public array $fields = [];

    public ?Arguments $arguments = null;

    public function __construct(?string $name = null, ?array $arguments = null)
    {
        if ($name) {
            $this->name = $name;
        }

        if ($arguments) {
            $this->setArguments($arguments);
        }
    }

    public function addField(Field $field): Node
    {
        array_push($this->fields, $field);

        return $this;
    }

    public function addFields(array $fields): Node
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }

        return $this;
    }

    public function setArguments(array $arguments): Node
    {
        $this->arguments = new Arguments($arguments);

        return $this;
    }

    public function toString(): string
    {
        $return = '';

        if ($this->hasFields()) {
            $return .= $this->name;

            if ($this->hasArguments()) {
                $return .= $this->arguments->toString();
            }

            $return .= ' {' . PHP_EOL;
            foreach ($this->fields as $field) {
                $return .= $field->toString();
            }
            $return .= '}' . PHP_EOL;

        } else {
            $return .= $this->name . PHP_EOL;
        }

        return $return;
    }

    protected function hasArguments(): bool
    {
        return ! is_null($this->arguments);
    }

    protected function hasFields(): bool
    {
        return ! empty($this->fields);
    }
}
