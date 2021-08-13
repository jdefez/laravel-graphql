<?php

namespace Jdefez\Graphql;

class Node
{
    public string $name;

    public array $fields = [];

    public ?Arguments $arguments = null;

    protected ?Node $parent = null;

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

    public function extractCallback(?array &$arguments = null): ?callable
    {
        $callback = null;
        if (! empty($arguments)
            && is_callable($arguments[count($arguments) - 1])
        ) {
            $callback = array_pop($arguments);
            if (isset($arguments[0])) {
                $arguments = $arguments[0];
            }
        }

        return $callback;
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
            $depth = $this->getParentsCount($this->parent);
            //$return .= $this->indent($this->name . "(" . $depth . ")", $depth);
            $return .= $this->indent($this->name, $depth);

            if ($this->hasArguments()) {
                $return .= $this->arguments->toString();
            }

            $return .= ' {' . PHP_EOL;
            foreach ($this->fields as $field) {
                $return .= $field->toString();
            }
            $return .= $this->indent('}', $depth) . PHP_EOL;

        } else {
            $depth = $this->getParentsCount($this->parent);
            //$return .= $this->indent($this->name . "(" . $depth . ")", $depth) . PHP_EOL;
            $return .= $this->indent($this->name, $depth) . PHP_EOL;
        }

        return $return;
    }

    protected function indent(string $string, int $depth): string
    {
        $len = $depth * 2;
        $len += strlen($string);
        return str_pad($string, $len, " ", STR_PAD_LEFT);
    }

    protected function hasParent(): bool
    {
        return ! is_null($this->parent);
    }

    protected function getParentsCount(Node $parent): int
    {
        $count = 1;
        if ($parent->hasParent()) {
            $count ++;
            $this->getParentsCount($parent->parent);
        }
        return $count;
    }

    protected function setParent(Node $parent): void
    {
        $this->parent = $parent;
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
