<?php

namespace Jdefez\LaravelGraphql\tests\Unit;

use Jdefez\LaravelGraphql\Inputs\Inputable;
use Jdefez\LaravelGraphql\tests\Inputs\CommiteeUserInput;
use Jdefez\LaravelGraphql\tests\Inputs\MandateDefinitionInput;
use Jdefez\LaravelGraphql\tests\Inputs\MandateInput;
use Jdefez\LaravelGraphql\tests\Inputs\UserInput;
use Jdefez\LaravelGraphql\tests\TestCase;

class InputTest extends TestCase
{
    private Inputable $input;

    public function setUp(): void
    {
        parent::setUp();

        $this->input = new UserInput(
            firstname: 'Anita',
            lastname: 'Badnews',
            email: 'abadnews@gmail.com'
        );
    }

    /** @test */
    public function it_renders_base_attributes(): void
    {
        $this->assertEquals(
            [
                'firstname' => 'Anita',
                'lastname' => 'Badnews',
                'email' => 'abadnews@gmail.com',
            ],
            $this->input->toArray()
        );
    }

    /** @test */
    public function connect_relation_renders_has_array_of_int(): void
    {
        $definition = (new MandateDefinitionInput(
            label: 'some mandate definitin name',
            credit: 1440,
        ))->connect('mandates', 12, 156, 2041);

        $this->assertEquals(
            [
                'label' => 'some mandate definitin name',
                'credit' => 1440,
                'mandates' => [
                    'connect' => [12, 156, 2041]
                ]
            ],
            $definition->toArray()
        );
    }

    /** @test */
    public function connect_relation_renders_has_an_array_of_objects(): void
    {
        // ManyToMany (pivot table)
        $this->input->connect(
            'commitees',
            new CommiteeUserInput(
                commitee_id: 1230,
                matricule: 'k27',
                role: 'user',
            ),
            new CommiteeUserInput(
                commitee_id: 400
            ),
        );

        $this->assertEquals(
            [
                'firstname' => 'Anita',
                'lastname' => 'Badnews',
                'email' => 'abadnews@gmail.com',
                'commitees' => [
                    'connect' => [
                        [
                            'commitee_id' => 1230,
                            'matricule' => 'k27',
                            'role' => 'user',
                        ],
                        [
                            'commitee_id' => 400,
                        ]
                    ]
                ]
            ],
            $this->input->toArray()
        );
    }

    /** @test */
    public function connect_relation_renders_has_an_single_objects(): void
    {
        $this->input->connect(
            'commitees',
            new CommiteeUserInput(
                commitee_id: 1230,
                matricule: 'k27',
                role: 'user',
            )
        );

        $this->assertEquals(
            [
                'firstname' => 'Anita',
                'lastname' => 'Badnews',
                'email' => 'abadnews@gmail.com',
                'commitees' => [
                    'connect' => [
                        'commitee_id' => 1230,
                        'matricule' => 'k27',
                        'role' => 'user',
                    ]
                ]
            ],
            $this->input->toArray()
        );
    }

    /** @test */
    public function connect_relation_renders_has_one_int(): void
    {
        $input = (new MandateInput('Representant syndical', 960))
            ->connect('mandateDefinition', 112)
            ->connect('commitee', 1);

        $this->assertEquals(
            [
                'mandateDefinition' => ['connect' => 112],
                'commitee' => ['connect' => 1],
                'credit' => 960,
                'label' => 'Representant syndical'
            ],
            $input->toArray()
        );
    }

    /** @test */
    public function disconnect_relation_is_rendered(): void
    {
        $this->input->disconnect('mandates', 2, 3);

        $this->assertEquals(
            [
                'firstname' => 'Anita',
                'lastname' => 'Badnews',
                'email' => 'abadnews@gmail.com',
                'mandates' => [
                    'disconnect' => [2, 3]
                ]
            ],
            $this->input->toArray()
        );
    }

    /** @test */
    public function delete_relation_is_redered(): void
    {
        $this->input->delete('mandates', 2, 3);

        $this->assertEquals(
            [
                'firstname' => 'Anita',
                'lastname' => 'Badnews',
                'email' => 'abadnews@gmail.com',
                'mandates' => [
                    'delete' => [2, 3]
                ]
            ],
            $this->input->toArray()
        );
    }

    /** @test */
    public function it_renders_sync_magic_method(): void
    {
        $this->input->syncCommitees(6, 7);

        $this->assertEquals(
            [
                'firstname' => 'Anita',
                'lastname' => 'Badnews',
                'email' => 'abadnews@gmail.com',
                'commitees' => [
                    'sync' => [6, 7]
                ]
            ],
            $this->input->toArray()
        );
    }

    /** @test */
    public function sync_relation_renders_has_an_array_of_objects(): void
    {
        $this->input->sync(
            'commitees',
            new CommiteeUserInput(
                commitee_id: 2,
                matricule: 'qjx212',
                role: 'user',
            ),
            new CommiteeUserInput(
                commitee_id: 3,
                matricule: 'qjx212',
                role: 'user',
            )
        );

        $this->assertEquals(
            [
                'firstname' => 'Anita',
                'lastname' => 'Badnews',
                'email' => 'abadnews@gmail.com',
                'commitees' => [
                    'sync' => [
                        [
                            'commitee_id' => 2,
                            'matricule' => 'qjx212',
                            'role' => 'user',
                        ],
                        [
                            'commitee_id' => 3,
                            'matricule' => 'qjx212',
                            'role' => 'user',
                        ],
                    ]
                ]
            ],
            $this->input->toArray()
        );
    }

    /** @test */
    public function sync_relation_renders_has_an_single_objects(): void
    {
        $this->input->sync('commitees', new CommiteeUserInput(
            commitee_id: 2,
            matricule: 'qjx212',
            role: 'user',
        ));

        $this->assertEquals(
            [
                'firstname' => 'Anita',
                'lastname' => 'Badnews',
                'email' => 'abadnews@gmail.com',
                'commitees' => [
                    'sync' => [
                        'commitee_id' => 2,
                        'matricule' => 'qjx212',
                        'role' => 'user',
                    ]
                ]
            ],
            $this->input->toArray()
        );
    }

    /** @test */
    public function sync_relation_renders_has_one_int(): void
    {
        $this->input->sync('commitees', 6);

        $this->assertEquals(
            [
                'firstname' => 'Anita',
                'lastname' => 'Badnews',
                'email' => 'abadnews@gmail.com',
                'commitees' => [
                    'sync' => 6
                ]
            ],
            $this->input->toArray()
        );
    }

    /** @test */
    public function sync_relation_renders_has_an_array_of_int(): void
    {
        $this->input->sync('commitees', 6, 13);

        $this->assertEquals(
            [
                'firstname' => 'Anita',
                'lastname' => 'Badnews',
                'email' => 'abadnews@gmail.com',
                'commitees' => [
                    'sync' => [6, 13]
                ]
            ],
            $this->input->toArray()
        );
    }

    /** @test */
    public function sync_without_detaching_method_is_rendered()
    {
        $this->input->syncWithoutDetaching('commitees', 6, 13);

        $this->assertEquals(
            [
                'firstname' => 'Anita',
                'lastname' => 'Badnews',
                'email' => 'abadnews@gmail.com',
                'commitees' => [
                    'syncWithoutDetaching' => [6, 13]
                ]
            ],
            $this->input->toArray()
        );
    }

    /** @test */
    public function it_renders_upsert_relation(): void
    {
        $this->input->upsert(
            'mandates',
            (new MandateInput(
                label: 'Representant syndical',
                credit: 960,
                id: 3,
            ))->connect('mandateDefinition', 1)
                ->connect('commitee', 1),
            (new MandateInput(
                label: 'Elu titulaire',
                credit: 1440,
                id: 4,
            ))->connect('mandateDefinition', 2)
                ->connect('commitee', 1),
        );

        $this->assertEquals(
            [
                'firstname' => 'Anita',
                'lastname' => 'Badnews',
                'email' => 'abadnews@gmail.com',
                'mandates' => [
                    'upsert' => [
                        [
                            'mandateDefinition' => ['connect' => 1],
                            'commitee' => ['connect' => 1],
                            'credit' => 960,
                            'label' => 'Representant syndical',
                            'id' => 3,
                        ],
                        [
                            'mandateDefinition' => ['connect' => 2],
                            'commitee' => ['connect' => 1],
                            'credit' => 1440,
                            'label' => 'Elu titulaire',
                            'id' => 4,
                        ]
                    ]
                ]
            ],
            $this->input->toArray()
        );
    }

    /** @test */
    public function it_renders_update_relation(): void
    {
        $this->input->update(
            'mandates',
            (new MandateInput(
                label: 'Representant syndical',
                credit: 960,
                id: 3,
            ))->connect('mandateDefinition', 1)
                ->connect('commitee', 1),
            (new MandateInput(
                label: 'Elu titulaire',
                credit: 1440,
                id: 4,
            ))->connect('mandateDefinition', 2)
                ->connect('commitee', 1),
        );

        $this->assertEquals(
            [
                'firstname' => 'Anita',
                'lastname' => 'Badnews',
                'email' => 'abadnews@gmail.com',
                'mandates' => [
                    'update' => [
                        [
                            'mandateDefinition' => ['connect' => 1],
                            'commitee' => ['connect' => 1],
                            'credit' => 960,
                            'label' => 'Representant syndical',
                            'id' => 3,
                        ],
                        [
                            'mandateDefinition' => ['connect' => 2],
                            'commitee' => ['connect' => 1],
                            'credit' => 1440,
                            'label' => 'Elu titulaire',
                            'id' => 4,
                        ]
                    ]
                ]
            ],
            $this->input->toArray()
        );
    }

    /** @test */
    public function it_renders_create_relation(): void
    {
        $this->input->create(
            'mandates',
            (new MandateInput('Representant syndical', 960))
                ->connect('mandateDefinition', 1)
                ->connect('commitee', 1),
            (new MandateInput('Elu titulaire', 1440))
                ->connect('mandateDefinition', 2)
                ->connect('commitee', 1),
        );

        $this->assertEquals(
            [
                'firstname' => 'Anita',
                'lastname' => 'Badnews',
                'email' => 'abadnews@gmail.com',
                'mandates' => [
                    'create' => [
                        [
                            'mandateDefinition' => ['connect' => 1],
                            'commitee' => ['connect' => 1],
                            'credit' => 960,
                            'label' => 'Representant syndical'
                        ],
                        [
                            'mandateDefinition' => ['connect' => 2],
                            'commitee' => ['connect' => 1],
                            'credit' => 1440,
                            'label' => 'Elu titulaire'
                        ]
                    ]
                ]
            ],
            $this->input->toArray()
        );
    }
}
