<?php

namespace Jdefez\LaravelGraphql\tests\Unit;

use Jdefez\LaravelGraphql\Facades\Graphql;
use Jdefez\LaravelGraphql\QueryBuilder\Builder;
use Jdefez\LaravelGraphql\tests\TestCase;

class GraphqlQueryBuilderTest extends TestCase
{
    /** @test */
    public function it_can_instanciate_a_query_builder()
    {
        return $this->assertInstanceOf(Builder::class, Graphql::query());
    }

    /** @test */
    public function it_can_build_a_query()
    {
        $query = Builder::query()
            ->user(
                ['id' => 1],
                fn (Builder $user) => $user
                    ->email()
                    ->name()
                    ->id()
            );

        $this->assertEquals(
            'query { user(id: 1) { email name id }}',
            $query->toString()
        );
    }

    /** @test */
    public function it_can_build_a_mutation()
    {
        $query = Builder::mutation([
            '$name' => 'String!', '$email' => 'String!'
        ])->createUser(
            ['name' => '$name', 'email' => '$email'],
            fn (Builder $user) => $user
                ->name()
                ->email()
        );

        $this->assertEquals(
            'mutation($name: String!, $email: String!) { createUser(name: $name, email: $email) { name email }}',
            $query->toString()
        );
    }
}
