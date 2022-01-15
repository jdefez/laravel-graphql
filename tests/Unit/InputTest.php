<?php

namespace Jdefez\LaravelGraphql\tests\Unit;

use Jdefez\LaravelGraphql\Inputs\BaseInput;
use Jdefez\LaravelGraphql\Inputs\BaseInputCollection;
use Jdefez\LaravelGraphql\Inputs\Inputable;
use Jdefez\LaravelGraphql\Inputs\InputableCollection;
use Jdefez\LaravelGraphql\tests\TestCase;

class InputTest extends TestCase
{
    private Inputable $input;

    public function setUp(): void
    {
        parent::setUp();

        $this->input = new class(
            'jean',
            'defez',
            'jdefez@gmail.com'
        ) extends BaseInput implements Inputable
        {
            public function __construct(
                public string $firstname,
                public string $lastname,
                public string $email,
                public ?int $id = null,
            ) {
            }

            public function toArray(): array
            {
                return parent::relationsToArray([
                    'firstname' => $this->firstname,
                    'lastname' => $this->lastname,
                    'email' => $this->email,
                    'id' => $this->id
                ]);
            }
        };
    }

    /** @test */
    public function it_renders_base_attributes()
    {
        $this->assertEquals(
            [
                'firstname' => 'jean',
                'lastname' => 'defez',
                'email' => 'jdefez@gmail.com',
            ],
            $this->input->toArray()
        );
    }

    /** @test */
    public function relation_is_not_rendered_when_collection_is_empty()
    {
        $this->input->create(
            new class(
                'mandates',
                [
                    // empty collection of mandates input
                ]
            ) extends BaseInputCollection implements InputableCollection {
                public function __construct(
                    public string $name,
                    public array $inputs,
                ) {
                }
            }
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
            new class(
                'mandates',
                [
                    // mandates input to be used
                ]
            ) extends BaseInputCollection implements InputableCollection {
                public function __construct(
                    public string $name,
                    public array $inputs,
                ) {
                }
            }
        );

        $this->assertEquals(
            [
                'firstname' => 'jean',
                'lastname' => 'defez',
                'email' => 'jdefez@gmail.com',
                'mandates' => [
                    'create' => [
                        [
                            'credit' => 960,
                            'label' => 'Representant syndical'
                        ],
                        [
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
