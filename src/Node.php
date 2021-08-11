<?php

namespace Jdefez\Graphql;

class Node
{
    public array $fields = [];

    public function addFields(array $fields)
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }
    }

    public function addField(Field $field)
    {
        array_push($this->fields, $field);
    }

    public function setArguments(array $arguments): Node
    {
        $this->arguments = new Arguments($arguments);

        return $this;
    }

    public function hasArguments(): bool
    {
        return ! is_null($this->arguments);
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

    protected function hasFields(): bool
    {
        return ! empty($this->fields);
    }
}
