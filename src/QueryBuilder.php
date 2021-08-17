<?php

namespace Jdefez\Graphql;

class QueryBuilder extends Field
{
    public static function query(): QueryBuilder
    {
        return new self();
    }

    public function toString(bool $ugglify = true): string
    {
        $return = 'QUERY {' . PHP_EOL;
        foreach ($this->fields as $field) {
            $return .= $field->toString();
        }
        $return .= '}';

        if ($ugglify) {
            $return = str_replace(PHP_EOL, '', $return);
            $return = preg_replace('#\s+#', ' ', $return);
        }

        return $return;
    }

    public function dump(): string
    {
        return $this->toString(false);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
