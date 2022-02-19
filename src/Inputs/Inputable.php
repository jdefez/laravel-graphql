<?php

namespace Jdefez\LaravelGraphql\Inputs;

interface Inputable
{
    /**
     * @param bool|int[] $inputs
     */
    public function disconnect(string $relationName, bool|array $inputs): BaseInput;

    public function connect(string $relationName, Inputable|int ...$inputs): BaseInput;

    /**
     * @param bool|int[] $inputs
     */
    public function delete(string $relationName, bool|array $inputs): BaseInput;

    /**
     * @param Inputable|array<Inputable>|int $inputs
     */
    public function syncWithoutDetaching(string $relationName, Inputable|array|int ...$inputs): BaseInput;

    /**
     * @param Inputable|array<Inputable>|int $inputs
     */
    public function sync(string $relationName, Inputable|array|int ...$inputs): BaseInput;

    public function create(string $relationName, Inputable ...$inputs): BaseInput;

    public function update(string $relationName, Inputable ...$inputs): BaseInput;

    public function upsert(string $relationName, Inputable ...$inputs): BaseInput;

    public function toArray(): array;
}
