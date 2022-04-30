<?php

namespace Jdefez\LaravelGraphql\Tests\Unit;

// use Jdefez\LaravelGraphql\Facades\Graphql;
use Jdefez\LaravelGraphql\QueryBuilder\Builder;
use Jdefez\LaravelGraphql\Tests\TestCase;
use Jdefez\LaravelGraphql\QueryBuilder\Unquoted;

class BuilderTest extends TestCase
{
    /** @test */
    public function it_can_instanciate_a_query_builder()
    {
        return $this->assertInstanceOf(Builder::class, Builder::query());
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
            $query->toString(true)
        );
    }

    /** @test */
    public function it_can_build_query_using_a_sub_builder()
    {
        $address_fields = Builder::make(['trashed' => new Unquoted('WITH')])
            ->zipcode()
            ->street()
            ->city();

        $query = Builder::query()
            ->user(['id' => 1],
                fn (Builder $user) => $user
                    ->email()
                    ->name()
                    ->id()
            )->addresses(
                $address_fields
            );

        $this->assertEquals(
            'query { user(id: 1) { email name id } addresses(trashed: WITH) { zipcode street city }}',
            $query->toString(true)
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
            $query->toString(true)
        );
    }
}
