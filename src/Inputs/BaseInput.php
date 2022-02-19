<?php

namespace Jdefez\LaravelGraphql\Inputs;

use Exception;
use ReflectionClass;
use ReflectionProperty;

/**
 * from lighthouse doc
 * -------------------
 * Laravel's sync(), syncWithoutDetach() or connect() methods allow you to
 * pass an array where the keys are IDs of related models and the values
 * are pivot data.
 *
 * Lighthouse exposes this capability through the nested
 * operations on many-to-many relations. Instead of passing just a list of
 * ids, you can define an input type that also contains pivot data.
 *
 * It must contain a field called id to contain the ID of the related
 * model, all other fields will be inserted into the pivot table.
 *
 * -----------
 * relations
 * -----------
 * _
 * HasOne:
 *  - create: CreatePhoneInput
 *  - update: UpdatePhoneInput
 *  - upsert: UpsertPhoneInput
 *  - delete: ID
 *
 * _
 * HasMany:
 *  - create: [CreatePostInput!]
 *  - update: [UpdatePostInput!]
 *  - upsert: [UpsertPostInput!]
 *  - delete: [ID!]
 *  - connect: [ID!]
 *  - disconnect: [ID!]
 *
 * _
 * BelongsToMany:
 *  - create: [CreateAuthorInput!]
 *  - connect: [ID!]
 *  - update: [UpdateAuthorInput!]
 *  - upsert: [UpsertAuthorInput!]
 *  - sync: [ID!]
 *  - syncWithoutDetaching: [ID!]
 *  - delete: [ID!]
 *  - disconnect: [ID!]
 *
 * _
 * MorphTo:
 *  - connect: ConnectImageableInput
 *  - disconnect: Boolean
 *  - delete: Boolean
 *
 * _
 * MorphMany:
 *  - create: [CreateAuthorInput!]
 *  - upsert: [UpsertAuthorInput!]
 *  - connect: [ID!]
 *  - sync: [ID!] to ensure only the given IDs will be contained withing the relation.
 *
 * -----------
 * API
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
 *
 *   what a bout magic methods like: createUsers([...])
 */

abstract class BaseInput implements Inputable
{
    public const EXCLUDE_NULL_PROPERTIES = true;

    public const RENDER_ALL_PROPERTIES = false;

    private array $relationsNames = [
        // note: has to remain sorted by longest name
        'syncWithoutDetaching',
        'disconnect',
        'connect',
        'create',
        'update',
        'upsert',
        'delete',
        'sync',
    ];

    private array $relations = [];

    protected bool $toArrayStrategy = self::RENDER_ALL_PROPERTIES;

    public function toArray(): array
    {
        return $this->relationsToArray(
            $this->getProperties($this)
        );
    }

    public function __call(string $name, array $arguments): BaseInput
    {
        list($method, $relation) = $this->extractCallParameters($name);

        if (!$method) {
            throw new Exception(sprintf(
                'Unexpected call to `%s` method',
                $name
            ));
        }

        return $this->{$method}($relation, ...$arguments);
    }

    public function syncWithoutDetaching(
        string $relationName,
        Inputable|int ...$inputs
    ): BaseInput {
        return $this->appendRelation('syncWithoutDetaching', $relationName, $inputs);
    }

    public function sync(
        string $relationName,
        Inputable|int ...$inputs
    ): BaseInput {
        return $this->appendRelation('sync', $relationName, $inputs);
    }

    public function connect(
        string $relationName,
        Inputable|int ...$inputs
    ): BaseInput {
        return $this->appendRelation('connect', $relationName, $inputs);
    }

    public function disconnect(string $relationName, bool|int ...$inputs): BaseInput
    {
        $this->relations[$relationName]['disconnect'] = $inputs;

        return $this;
    }

    public function delete(string $relationName, bool|int ...$inputs): BaseInput
    {
        $this->relations[$relationName]['delete'] = $inputs;

        return $this;
    }

    public function create(string $relationName, Inputable ...$inputs): BaseInput
    {
        return $this->appendRelation('create', $relationName, $inputs);
    }

    public function update(string $relationName, Inputable ...$inputs): BaseInput
    {
        return $this->appendRelation('update', $relationName, $inputs);
    }

    public function upsert(string $relationName, Inputable ...$inputs): BaseInput
    {
        return $this->appendRelation('upsert', $relationName, $inputs);
    }

    protected function relationsToArray(array $attributes): array
    {
        return array_merge($attributes, $this->relations);
    }

    protected function appendRelation(
        string $type,
        string $name,
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

            if (
                is_null($value)
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
            fn (ReflectionProperty $prop): string => $prop->name,
            $properties
        );
    }

    private function extractCallParameters(string $name): array
    {
        $relation = null;
        $method = $this->extractCalledMethod($name);

        if ($method) {
            $relation = $this->toSnakeCase(str_replace($method, '', $name));
        }

        return [$method, $relation];
    }

    private function extractCalledMethod(string $name): ?string
    {
        foreach ($this->relationsNames as $method) {
            if (strstr($name, $method) !== false) {
                return $method;
            }
        }

        return null;
    }

    private function toSnakeCase(string $input): string
    {
        return strtolower(preg_replace(
            ['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'],
            '$1_$2',
            $input
        ));
    }
}
