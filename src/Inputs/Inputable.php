<?php

namespace Jdefez\LaravelGraphql\Inputs;

interface Inputable
{
    public function disconnect(string $relationName, bool $input): BaseInput;

    public function connect(string $relationName, Inputable|int ...$inputs): BaseInput;

    public function sync(string $relationName, Inputable|int ...$inputs): BaseInput;

    public function create(string $relationName, Inputable ...$inputs): BaseInput;

    public function update(string $relationName, Inputable ...$inputs): BaseInput;

    public function upsert(string $relationName, Inputable ...$inputs): BaseInput;

    public function toArray(): array;
}
