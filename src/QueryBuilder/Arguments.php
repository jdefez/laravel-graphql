<?php

namespace Jdefez\LaravelGraphql\QueryBuilder;

use Illuminate\Support\Str;

class Arguments
{
    protected array $scalars = [
        'String', 'Int', 'Float', 'Boolean', 'ID'
    ];

    public function __construct(
        protected array $values
    ) {
    }

    public function toString(): string
    {
        $return = [];
        foreach ($this->values as $key => $value) {
            if (is_array($value)) {
                if (!array_is_list($value)) {
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

    protected function addQuote($value): string
    {
        if ($value
            && is_string($value)
            && !Str::of($value)->startsWith('$')
            && !$this->isScalar($value)
            && !$this->isCustomType($value)
        ) {
            $value = '"' . $value . '"';
        }

        return $value;
    }

    protected function isScalar(string $type): bool
    {
        return Str::contains($type, $this->scalars);
    }

    protected function isCustomType(string $string): bool
    {
        $letter = substr($string, 0, 1);

        return ! empty((string) Str::of($letter)->match('/[a-zA-Z]/'))
            && self::isUpperCase($letter);
    }

    public static function isUpperCase(string $string): bool
    {
        return strtoupper($string) === $string;
    }
}
