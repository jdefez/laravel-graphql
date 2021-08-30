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
        $query = Builder::query()
            ->user(
                ['id' => 1],
                fn (Builder $user) => $user
                ->email()
                ->name()
                ->id()
                ->posts(
                    fn ($post) => $post
                    ->id()
                    ->title()
                )
            );

        dump($query->toString());

        $response = Graphql::request($this->api_url)
            ->get($query->toString());

        dump($response);

        $this->assertTrue(true);
    }

    /** @test */
    public function it_can_create_a_user()
    {
        $query = Builder::mutation([
            '$input' => 'UserInput',
        ])->createUser(
            ['input' => '$input'],
            fn (Builder $user) => $user
                    ->name()
                    ->email()
        );

        dump($query->toString());

        $response = Graphql::request($this->api_url)
            ->post($query->toString(), [
                'input' => ['name' => 'test', 'email' => 'test2@gmail.com']
            ]);

        dump($response);

        $this->assertTrue(true);
    }

    /** @test */
    public function it_can_delete_a_user()
    {
        $query = Builder::mutation(['$id' => 'ID!'])
            ->deleteUser(['id' => '$id'], fn (Builder $user) => $user
                ->id()
                ->email()
            );

        dump($query->toString());

        $response = Graphql::request($this->api_url)
            ->delete($query->toString(), ['id' => 3]);

        dump($response);

        $this->assertTrue(true);
    }
}
