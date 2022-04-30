<?php

namespace Jdefez\LaravelGraphql\QueryBuilder;

use Illuminate\Support\Str;

class Arguments
{
    protected array $scalars = [
        'String', 'Int', 'Float', 'Boolean', 'ID'
    ];

    public function __construct(
        protected ?array $values = null
    ) {
    }

    public function __toString(): string
    {
        if (!$this->values) {
            return '';
        }

        return '(' . implode(', ', $this->render($this->values)) . ')';
    }

    protected function render(array $arr): array
    {
        $return = [];

        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $pattern = '%s: {%s}';

                if (array_is_list($value)) {
                    $pattern = '%s: [%s]';
                }

                $return[] = sprintf(
                    $pattern,
                    $key,
                    implode(', ', $this->render($value))
                );
            } else {
                $value = $this->addQuote($value);

                if (!is_int($key)) {
                    $value = sprintf('%s: %s', $key, $value);
                }

                $return[] = $value;
            }
        }

        return $return;
    }

    protected function addQuote($value): string
    {
        if (!is_numeric($value)
            && !Str::of($value)->startsWith('$')
            && !$value instanceof Unquoted
            && !$this->isScalar($value)
        ) {
            $value = '"' . $value . '"';
        }

        return $value;
    }

    protected function isScalar(string $type): bool
    {
        return Str::contains($type, $this->scalars);
    }

    public static function isUpperCase(string $string): bool
    {
        return strtoupper($string) === $string;
    }
}
