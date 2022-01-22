<?php

namespace Jdefez\LaravelGraphql\Inputs;

/**
    API
    (new UserInput(
        firstname: 'jean',
        lastname: 'defez',
        email: 'jdefez@gmail.com'
    ))->connect('relationName', $input, 134, ...)
      ->sync('relationName', 1, $input, 3, 4)
      ->create('relationName', $input, $input, ...)
      ->toArray();
 */

abstract class BaseInput implements Inputable
{
    private array $relations = [];

    abstract public function toArray(): array;

    public function connect(
        string $relationName,
        Inputable|int|array ...$inputs
    ): BaseInput {
        return $this->appendRelation('connect', $relationName, $inputs);
    }

    public function disconnect(string $relationName, bool $input): BaseInput
    {
        $this->relations[$relationName]['disconnect'] = $input;

        return $this;
    }

    public function sync(
        string $relationName,
        Inputable|array|int ...$inputs
    ): BaseInput {
        return $this->appendRelation('sync', $relationName, $inputs);
    }

    public function delete(string $relationName, bool $input): BaseInput
    {
        $this->relations[$relationName]['disconnect'] = $input;

        return $this;
    }

    /**
     * @param array<Inputable> $inputs
     */
    public function create(string $relationName, Inputable ...$inputs): BaseInput
    {
        return $this->appendRelation('create', $relationName, $inputs);
    }

    /**
     * @param array<Inputable> $inputs
     */
    public function update(string $relationName, Inputable ...$inputs): BaseInput
    {
        return $this->appendRelation('update', $relationName, $inputs);
    }

    /**
     * @param array<Inputable> $inputs
     */
    public function upsert(string $relationName, Inputable ...$inputs): BaseInput
    {
        return $this->appendRelation('upsert', $relationName, $inputs);
    }

    protected function relationsToArray(array $attributes): array
    {
        return array_merge(
            $this->forgetIdWhenNull($attributes),
            $this->relations
        );
    }

    protected function forgetIdWhenNull(array $attributes): array
    {
        if (array_key_exists('id', $attributes)
            && is_null($attributes['id'])
        ) {
            unset($attributes['id']);
        }

        return $attributes;
    }

    protected function appendRelation(
        string $relationType,
        string $relationName,
        array $inputs
    ): BaseInput {
        if (!empty($inputs)) {
            $inputs = collect($inputs)->map(function (mixed $item) {
                if ($item instanceof Inputable) {
                    $item = $item->toArray();
                }

                return $item;
            });

            if ($inputs->count() === 1) {
                $this->relations[$relationName][$relationType] = $inputs->first();
            } else {
                $this->relations[$relationName][$relationType] = $inputs->toArray();
            }
        }

        return $this;
    }
}
