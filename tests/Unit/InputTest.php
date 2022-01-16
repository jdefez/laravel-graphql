<?php

namespace Jdefez\LaravelGraphql\tests\Unit;

use Jdefez\LaravelGraphql\Inputs\Inputable;
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
            'Anita',
            'Badnews',
            'abadnews@gmail.com'
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
