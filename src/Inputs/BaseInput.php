<?php

namespace Jdefez\LaravelGraphql\Inputs;

/*
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

/*
    api

    (new UserInput(
        firstname: 'jean',
        lastname: 'defez',
        email: 'jdefez@gmail.com'
    ))->connect($inputCollection)
      ->sync($inputCollection)
      ->create($inputCollection)
      ->toArray();

    (new UserInput(
        firstname: 'jean',
        lastname: 'defez',
        email: 'jdefez@gmail.com'
    ))->connect('relationName', $input, $input, ...)
      ->sync('relationName', 1, 2, 3, 4)
      ->create('relationName', $input, $input, ...)
      ->toArray();
*/

abstract class BaseInput implements Inputable
{
    /** @var array */
    private array $relations = [];

    public abstract function toArray(): array;

    // todo: list possible types
    // InputableCollection|array<int>|int
    //
    // todo: this should:
    //  - havel a relation name
    //  - handle a list of models
    //  - InputableCollection is handled internaly
    public function connect(InputableCollection $collection): BaseInput
    {
        $this->setRelation('connect', $collection);

        return $this;
    }

    public function disconnect(string $relationName, bool $input): BaseInput
    {
        // todo: implement
        //
        // {
        //    $relationName: { disconnect: $input }
        // }
        return $this;
    }

    // todo: list possible types
    // InputableCollection|array<int>|int
    public function sync(InputableCollection $collection): BaseInput
    {
        $this->setRelation('sync', $collection);

        return $this;
    }

    public function delete(string $relationName, bool $input): BaseInput
    {
        // todo: implement

        // {
        //    $relationName: { delete: $input }
        // }

        return $this;
    }

    // todo: this should:
    //  - havel a relation name
    //  - handle a list of models
    //  - InputableCollection is handled internaly
    public function create(InputableCollection $collection): BaseInput
    {
        $this->setRelation('create', $collection);

        return $this;
    }

    // todo: this should:
    //  - havel a relation name
    //  - handle a list of models
    //  - InputableCollection is handled internaly
    public function update(InputableCollection $collection): BaseInput
    {
        $this->setRelation('update', $collection);

        return $this;
    }

    // todo: this should:
    //  - havel a relation name
    //  - handle a list of models
    //  - InputableCollection is handled internaly
    public function upsert(InputableCollection $collection): BaseInput
    {
        $this->setRelation('upsert', $collection);

        return $this;
    }

    public function relationsToArray(array $attributes): array
    {
        $attributes = $this->forgetIdWhenNull($attributes);

        foreach ($this->relations as $type => $collections) {
            foreach ($collections as $collection) {

                // todo: handle all collection types

                $attributes = $this->appendRelation(
                    $collection,
                    $attributes,
                    $type
                );
            }
        }

        return $attributes;
    }

    protected function appendRelation(
        InputableCollection $collection,
        array $attributes,
        string $relationType
    ): array {
        if (!$collection->isEmpty()) {
            $attributes[$collection->name] = [
                $relationType => $collection->toArray()
            ];
        }

        return $attributes;
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

    protected function setRelation(string $type, InputableCollection $collection): void
    {
        $this->relations[$type][] = $collection;
    }
}
