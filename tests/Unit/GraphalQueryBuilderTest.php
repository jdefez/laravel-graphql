<?php

namespace Jdefez\Graphql\Tests;

use Jdefez\Graphql\Facades\Graphql;
use Jdefez\Graphql\Field;
use Jdefez\Graphql\QueryBuilder;
use Jdefez\Graphql\tests\TestCase;

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
            'QUERY { user(id: 1) { email name id }}',
            $query->toString()
        );
    }
}