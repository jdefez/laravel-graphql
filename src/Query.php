<?php

namespace Jdefez\Graphql;

class Query
{
    protected array $fields = [];

    public function addFields(array $fields): Query
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }

        return $this;
    }

    public function addField(Field $field): Query
    {
        if (! is_array($this->fields)) {
            $this->fields = [];
        }

        array_push($this->fields, $field);

        return $this;
    }


    public function toString(): string
    {
        $return = 'query {' . PHP_EOL;
        foreach ($this->fields as $field) {
            $return .= $field->toString();
        }
        $return .= '}' . PHP_EOL;

        return $return;
    }

    protected function hasFields(): bool
    {
        return ! empty($this->fields);
    }
}
