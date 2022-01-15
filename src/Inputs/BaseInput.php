<?php

namespace Jdefez\LaravelGraphql\Inputs;

/*
Example

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

    (new UserInput(
      firstname: 'jean',
      lastname: 'defez',
      email: 'jdefez@gmail.com'
    ))->connect($inputCollection)
    ))->sync($inputCollection)
    ))->create($inputCollection)
      ->toArray();
*/

abstract class BaseInput implements Inputable
{
    /** @var array */
    private array $relations = [];

    public abstract function toArray(): array;

    public function connect(InputableCollection $collection): BaseInput
    {
        $this->setRelation('connect', $collection);

        return $this;
    }

    public function sync(InputableCollection $collection): BaseInput
    {
        $this->setRelation('sync', $collection);

        return $this;
    }

    public function create(InputableCollection $collection): BaseInput
    {
        $this->setRelation('create', $collection);

        return $this;
    }

    public function relationsToArray(array $attributes): array
    {
        $attributes = $this->forgetIdWhenNull($attributes);

        foreach ($this->relations as $type => $collections) {
            foreach ($collections as $collection) {
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

    protected function setRelation(string $type, InputableCollection $collection)
    {
        $this->relations[$type][] = $collection;
    }
}
