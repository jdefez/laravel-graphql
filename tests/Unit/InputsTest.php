<?php

namespace tests\Unit;

use Jdefez\LaravelGraphql\Inputs\Inputable;
use Jdefez\LaravelGraphql\examples\Inputs\UserInput;
use Jdefez\LaravelGraphql\tests\TestCase;

class InputsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_instanciate_an_inputable()
    {
        $input = (new UserInput(
            email: 'h.green@hotmail.com',
            firstname: 'Hank',
            lastname: 'Green',
        ));

        $this->assertInstanceOf(Inputable::class, $input);
        $this->assertEquals([
            'email' => 'h.green@hotmail.com',
            'firstname' => 'Hank',
            'lastname' => 'Green'
        ], $input->toArray());
    }
}
