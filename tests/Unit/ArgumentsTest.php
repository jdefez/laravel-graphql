<?php

namespace Jdefez\LaravelGraphql\tests\Unit;

use Jdefez\LaravelGraphql\QueryBuilder\Arguments;
use Jdefez\LaravelGraphql\Tests\TestCase;

class ArgumentsTest extends TestCase
{
    /** @test */
    public function it_renders_dates_in_assoc_arrays()
    {
        $args = new Arguments([
            'updated_at' => [
                'from' => '2021-08-06',
                'to' => '2021-08-07'
            ],
        ]);

        $this->assertEquals(
            '(updated_at: {from: "2021-08-06", to: "2021-08-07"})',
            $args->toString()
        );
    }

    /** @test */
    public function it_renders_custom_type_arguments()
    {
        $args = new Arguments([
            'trashed' => 'WITH',
        ]);

        $this->assertEquals(
            '(trashed: WITH)',
            $args->toString()
        );
    }

    /** @test */
    public function it_renders_scalars()
    {
        $args = new Arguments([
            'myBoolean' => 'Boolean'
        ]);

        $this->assertEquals(
            '(myBoolean: Boolean)',
            $args->toString()
        );
    }
}
