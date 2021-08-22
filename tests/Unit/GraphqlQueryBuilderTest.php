<?php

namespace Jdefez\LaravelGraphql\Tests;

use Jdefez\LaravelGraphql\Facades\Graphql;
use Jdefez\LaravelGraphql\Field;
use Jdefez\LaravelGraphql\QueryBuilder;
use Jdefez\LaravelGraphql\tests\TestCase;

class GraphqlQueryBuilderTest extends TestCase
{
    /** @test */
    public function it_can_instanciate_a_query_builder()
    {
        return $this->assertInstanceOf(QueryBuilder::class, Graphql::query());
    }

    /** @test */
    public function it_can_build_a_query()
    {
        $query = QueryBuilder::query()
            ->user(['id' => 1], fn(Field $user) => $user->email()
                ->name()
                ->id()
            );

        $this->assertEquals(
            'query { user(id: 1) { email name id }}',
            $query->toString()
        );
    }
}
