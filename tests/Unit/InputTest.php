<?php

namespace Jdefez\LaravelGraphql\tests\Unit;

use Jdefez\LaravelGraphql\Inputs\Inputable;
use Jdefez\LaravelGraphql\tests\Inputs\MandateInput;
use Jdefez\LaravelGraphql\tests\Inputs\MandateInputCollection;
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
    public function it_renders_base_attributes()
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
    public function create_relation_is_not_rendered_when_collection_is_empty()
    {
        $this->input->create(
            new MandateInputCollection(
                'mandates',
                [
                    // empty collection of mandates input
                ]
            )
        );

        $this->assertArrayNotHasKey(
            'mandates',
            $this->input->toArray()
        );
    }

    /** @test */
    public function it_renders_create_relation()
    {
        $this->input->create(
            new MandateInputCollection(
                'mandates',
                [
                    new MandateInput(
                        1,
                        'Representant syndical',
                        960
                    ),
                    new MandateInput(
                        2,
                        'Elu titulaire',
                        1440
                    ),
                ]
            )
        );

        $this->assertEquals(
            [
                'firstname' => 'Anita',
                'lastname' => 'Badnews',
                'email' => 'abadnews@gmail.com',
                'mandates' => [
                    'create' => [
                        [
                            'mandate_definition_id' => 1,
                            'credit' => 960,
                            'label' => 'Representant syndical'
                        ],
                        [
                            'mandate_definition_id' => 2,
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
