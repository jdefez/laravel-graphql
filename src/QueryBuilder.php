<?php

namespace Jdefez\LaravelGraphql;

class QueryBuilder extends Field
{
    public static function query(): QueryBuilder
    {
        return self::initialize('query');
    }

    public static function mutation(array $arguments): QueryBuilder
    {
        return self::initialize('mutation', $arguments);
    }

    public function toString(bool $ugglify = true): string
    {
        $return = $this->type;

        if ($this->hasArguments()) {
            $return .= $this->arguments->toString();
        }

        $return .= ' {' . PHP_EOL;
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

    protected static function initialize(string $type, ?array $arguments = null): QueryBuilder
    {
        $self = new self();

        $self->type = $type;

        if ($arguments) {
            $self->setArguments($arguments);
        }

        return $self;
    }
}
