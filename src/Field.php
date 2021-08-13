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
        // todo: refactor cf. QueryBuilder use setArguments
        $callback = null;
        if (! empty($arguments)
            && is_callable($arguments[count($arguments) - 1])
        ) {
            $callback = array_pop($arguments);
            if (isset($arguments[0])) {
                $arguments = $arguments[0];
            }
        }

        $field = new Field($name, $arguments);
        $this->addField($field);

        if ($callback) {
            $callback($field);
        }

        return $this;
    }
}
