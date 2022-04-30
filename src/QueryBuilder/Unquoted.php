<?php

namespace Jdefez\LaravelGraphql\QueryBuilder;

class Unquoted
{
    public function __construct(
        public mixed $value
    ) {
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
