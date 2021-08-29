<?php

namespace Jdefez\LaravelGraphql\QueryBuilder;

class Builder
{
    public ?string $name = null;

    public array $fields = [];

    public ?Arguments $arguments = null;

    protected ?Builder $parent = null;

    public function __construct(?string $name = null, ?array $arguments = null)
    {
        if ($name) {
            $this->name = $name;
        }

        if ($arguments) {
            $this->setArguments($arguments);
        }
    }

    public static function query(): Builder
    {
        return new self('query');
    }

    public static function mutation(array $arguments): Builder
    {
        return new self('mutation', $arguments);
    }

    public function __call(string $name, ?array $arguments = null): Builder
    {
        $callback = $this->extractCallback($arguments);
        $field = new self($name, $arguments);
        $field->setParent($this);
        $this->addField($field);

        if ($callback) {
            $callback($field);
        }

        return $this;
    }

    public function toString(bool $ugglify = true): string
    {
        $return = '';

        if ($this->hasFields()) {
            $depth = $this->getParentsCount($this);
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
            $depth = $this->getParentsCount($this);
            $return .= $this->indent($this->name, $depth) . PHP_EOL;
        }

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

    protected function addField(Builder $field): Builder
    {
        array_push($this->fields, $field);

        return $this;
    }

    protected function extractCallback(?array &$arguments = null): ?callable
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

    protected function setArguments(array $arguments): Builder
    {
        $this->arguments = new Arguments($arguments);

        return $this;
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

    protected function getParentsCount(Builder $child): int
    {
        $count = 0;
        while ($child->hasParent()) {
            $count ++;
            $child = $child->parent;
        }
        return $count;
    }

    protected function setParent(Builder $parent): void
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
