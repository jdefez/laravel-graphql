<?php

namespace Jdefez\LaravelGraphql\Inputs;

use ReflectionClass;
use ReflectionProperty;

/**
 * -----------
 * API example
 * -----------
 *
 * (new UserInput(
 *     firstname: 'jean',
 *     lastname: 'defez',
 *     email: 'jdefez@gmail.com'
 * ))->connect('relationName', $input, 134, ...)
 *   ->sync('relationName', 1, $input, 3, 4)
 *   ->create('relationName', $input, $input, ...)
 *   ...
 *   ->toArray();
 */

abstract class BaseInput implements Inputable
{
    public const EXCLUDE_NULL_PROPERTIES = true;

    public const RENDER_ALL_PROPERTIES = false;

    private array $relations = [];

    protected bool $toArrayStrategy = self::RENDER_ALL_PROPERTIES;

    public function toArray(): array
    {
        return $this->relationsToArray(
            $this->getProperties($this)
        );
    }

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
        return array_merge($attributes, $this->relations);
    }

    protected function appendRelation(string $type, string $name, array $inputs): BaseInput
    {
        if (!empty($inputs)) {
            $inputs = collect($inputs)->map(function (mixed $item) {
                if ($item instanceof Inputable) {
                    $item = $item->toArray();
                }

                return $item;
            });

            if ($inputs->count() === 1) {
                $this->relations[$name][$type] = $inputs->first();
            } else {
                $this->relations[$name][$type] = $inputs->toArray();
            }
        }

        return $this;
    }

    protected function getProperties(object $class): array
    {
        $list = [];

        foreach ($this->getPublicPropertiesNames($class) as $name) {
            $value = $this->{$name};

            // always forget id attribute when null

            if (strtolower($name) === 'id' && is_null($value)) {
                continue;
            }

            if (is_null($value)
                && $this->toArrayStrategy === self::EXCLUDE_NULL_PROPERTIES
            ) {
                continue;
            }

            $list[$name] = $value;
        }

        return $list;
    }

    protected function getPublicPropertiesNames(object $class): array
    {
        $properties = (new ReflectionClass($class))
            ->getProperties(ReflectionProperty::IS_PUBLIC);

        return array_map(
            fn (ReflectionProperty $prop) => $prop->name,
            $properties
        );
    }
}
