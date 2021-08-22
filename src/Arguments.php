<?php

namespace Jdefez\LaravelGraphql;

use Illuminate\Support\Str;

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
            } else {
                $value = $this->addQuote($value);
            }

            $return[] = sprintf('%s: %s', $key, $value);
        }

        return '(' . implode(', ', $return) . ')';
    }

    protected function assocToString(array $array): string
    {
        $return = [];
        foreach ($array as $key => $value) {
            $return[] = sprintf('%s: %s', $key, $this->addQuote($value));
        }

        return '{' . implode(', ', $return) . '}';
    }

    protected function sequentialToString(array $array): string
    {
        $values = array_values($array);
        $values = array_map(fn ($item) => $this->addQuote($item), $values);

        return '[' . implode(', ', $values) . ']';
    }

    protected function addQuote($value)
    {
        if ($value
            && is_string($value)
            && !Str::of($value)->startsWith('$')
        ) {
            $value = '"' . $value . '"';
        }

        return $value;
    }

    protected function isAssoc(array $array): bool
    {
        if ([] === $array) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }
}
