<?php

namespace Jdefez\LaravelGraphql\Tests\Unit;

use Jdefez\LaravelGraphql\Facades\Graphql;
use Jdefez\LaravelGraphql\QueryBuilder\Builder;
use Jdefez\LaravelGraphql\tests\TestCase;

class LocalTest extends TestCase
{
    public string $api_url = 'http://localhost/graphql';

    /** @test */
    public function it_can_fetch_a_user()
    {
        $request = Graphql::request($this->api_url);
        $query = Builder::query()
            ->user(['id' => 1], fn (Builder $user) => $user
                ->email()
                ->name()
                ->id()
                ->posts(fn ($post) => $post
                    ->id()
                    ->title()
                )
            );

        dd($query->toString());

        $response = $request->get($query->toString());

        dd($response);

        $this->assertTrue(true);
    }

    /** @test */
    public function it_can_create_a_user()
    {
        $request = Graphql::request($this->api_url);

        $query = Builder::mutation([
            '$name' => 'String!', '$email' => 'String!'
        ])->createUser(
            ['name' => '$name', 'email' => '$email'],
            fn (Builder $user) => $user
                    ->name()
                    ->email()
        );

        dd($query->toString());

        $response = $request->post($query->toString(), [
            'name' => 'test', 'email' => 'test1@gmail.com'
        ]);

        dd($response);
        $this->assertTrue(true);
    }
}
