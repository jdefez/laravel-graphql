<?php

namespace Jdefez\Graphql;

class Arguments
{
    protected $values = [];

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function toString(): string
    {
        $return = [];
        foreach ($this->values as $key => $value) {
            if (is_array($value)) {
                if ($this->isAssoc($value)) {
                    $value = $this->assocToString($value);
                } else {
                    $value = $this->sequentialToString($value);
                }
            } elseif (is_string($value)) {
                $value = '"' . $value . '"';
            }

            $return[] = sprintf('%s: %s', $key, $value);
        }

        return '(' . implode(', ', $return) . ')';
    }

    protected function isAssoc(array $array): bool
    {
        if ([] === $array) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }

    protected function assocToString(array $array): string
    {
        $return = [];
        foreach ($array as $key => $value) {
            if (is_string($value)) {
                $value = '"' . $value . '"';
            }

            $return[] = sprintf('%s: %s', $key, $value);
        }

        return '{' . implode(', ', $return) . '}';
    }

    protected function sequentialToString(array $array): string
    {
        $values = array_values($array);
        $values = array_map(
            fn ($item) => is_string($item) ? '"' . $item . '"' : $item,
            $values
        );

        return '[' . implode(', ', $values) . ']';
    }
}
