<?php

namespace Jdefez\Graphql;

class Field extends Query
{
    public string $name;

    public array $arguments = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function setName(string $name): Query
    {
        return new self($name);
    }

    public function setArguments(array $arguments): Query
    {
        $this->arguments = $arguments;

        return $this;
    }

    public function toString(): string
    {
        $return = '';

        if ($this->hasFields()) {
            $return .= '- ' . $this->name;

            if ($this->hasArguments()) {
                // todo: render arguments
            }

            $return . ' {' . PHP_EOL;
            foreach ($this->fields as $field) {
                $return .= $field->toString();
            }
            $return .= '}' . PHP_EOL;

        } else {
            $return .= '- ' . $this->name . PHP_EOL;
        }

        return $return;
    }

    public function hasArguments(): bool
    {
        return ! empty($this->arguments);
    }
}
