<?php

namespace Jdefez\LaravelGraphql\Tests\Unit;

use Jdefez\LaravelGraphql\QueryBuilder\Arguments;
use Jdefez\LaravelGraphql\QueryBuilder\Unquoted;
use Jdefez\LaravelGraphql\Tests\TestCase;

class ArgumentsTest extends TestCase
{
    /** @test */
    public function it_handles_deep_nested_arguments(): void
    {
        $this->assertEquals(
            '(filter: {code: {in: ["FR", "GB"]}})',
            (string) new Arguments([
                'filter' => [
                    'code' => [
                        'in' => ['FR', 'GB']
                    ]
                ]
            ])
        );
    }

    /** @test */
    public function it_renders_dates_in_assoc_arrays(): void
    {
        $this->assertEquals(
            '(updated_at: {from: "2021-08-06", to: "2021-08-07"})',
            (string) new Arguments([
                'updated_at' => [
                    'from' => '2021-08-06',
                    'to' => '2021-08-07'
                ],
            ])
        );
    }

    /** @test */
    public function it_renders_arguments_marked_as_unquoted(): void
    {
        $this->assertEquals(
            '(trashed: WITH)',
            (string) new Arguments(['trashed' => new Unquoted('WITH')])
        );
    }

    /** @test */
    public function it_renders_scalars(): void
    {
        $this->assertEquals(
            '(myBoolean: Boolean)',
            (string) new Arguments(['myBoolean' => 'Boolean'])
        );
    }
}
