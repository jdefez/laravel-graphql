<?php

namespace Jdefez\LaravelGraphql\Inputs;

abstract class BaseInput
{
    protected array $relations;

    abstract public function toArray(): array;

    public function syncWithoutDetaching($name, $value): self
    {
        $this->addRelation('syncWithoutDetaching', $name, $value);

        return $this;
    }

    public function disconnect($name, $value): self
    {
        $this->addRelation('disconnect', $name, $value);

        return $this;
    }

    public function connect($name, $value): self
    {
        $this->addRelation('connect', $name, $value);

        return $this;
    }

    public function upsert(string $name, $value): self
    {
        $this->addRelation('upsert', $name, $value);

        return $this;
    }

    public function update(string $name, $value): self
    {
        $this->addRelation('update', $name, $value);

        return $this;
    }

    public function create(string $name, $value): self
    {
        $this->addRelation('create', $name, $value);

        return $this;
    }

    public function sync($name, $value): self
    {
        $this->addRelation('sync', $name, $value);

        return $this;
    }

    protected function addRelation(string $type, string $name, $value): void
    {
        $this->relations[] = [
            $name => [
                $type => $value,
            ]
        ];
    }
}
