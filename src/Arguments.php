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
        $return = '(';

        foreach ($this->values as $key => $value) {
            $return .= sprintf('%s: %s',
                $key,
                $value
            );
        }

        $return .= ')';

        return $return;
    }
}
