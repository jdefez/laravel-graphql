<?php

namespace Jdefez\LaravelGraphql\Inputs;

/**
    json input examples

    {
        "input": {
            "firstname": "HUGO",
            "lastname": "DUPONT",
            "email": "hdupont@elior.fr",
            "managers": {
                "sync": [
                    {
                        "manager_id": 7
                    }
                ]
            },
            "commitees": {
                "sync": [
                    {
                        "commitee_id": 152,
                        "matricule": "00123456 - 9001",
                        "role": "user"
                    }
                ]
            },
            "mandates": {
                "create": [
                    {
                        "mandateDefinition": {
                            "connect" : 3
                        },
                        "commitee": {
                            "connect": 152
                        },
                        "credit": 960,
                        "label": "Repr\u00e9sentant syndical au CSEC"
                    }
                ]
            }
        }
    }

    mutation {
        updateUser(
            input: {
                id: 3
                name: "Phillip"
                posts: {
                    create: [{ title: "A new post" }]
                    update: [{ id: 45, title: "This post is updated" }]
                    delete: [8]
                    connect: [1, 2, 3]
                    disconnect: [6]
                }
            }
        ) {
            id
            posts {
                id
            }
        }
    }

    mutation {
        createPost(
            input: {
                title: "My new Post"
                authors: {
                    create: [{ name: "Herbert" }]
                    upsert: [{ id: 2000, name: "Newton" }]
                    connect: [123]
                }
            }
        ) {
            id
            authors {
                name
            }
        }
    }
 */

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

    public abstract function toArray(): array;

    /**
     * @param array<Inputable>|array<int> $inputs
     */
    public function connect(
        string $relationName,
        Inputable|int ...$inputs
    ): BaseInput {
        return $this->appendRelation('connect', $relationName, $inputs);
    }

    public function disconnect(string $relationName, bool $input): BaseInput
    {
        $this->relations[$relationName]['disconnect'] = $input;

        return $this;
    }

    /**
     * @param array<Inputable>|array<int> $inputs
     */
    public function sync(string $relationName, Inputable|int ...$inputs): BaseInput
    {
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
        if (
            array_key_exists('id', $attributes)
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
            $inputs = collect($inputs)
                ->map(function ($item) {
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
