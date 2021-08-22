<?php

namespace Jdefez\LaravelGraphql\Tests;

use Jdefez\LaravelGraphql\Facades\Graphql;
use Jdefez\LaravelGraphql\Field;
use Jdefez\LaravelGraphql\QueryBuilder;
use Jdefez\LaravelGraphql\tests\TestCase;

class LocalTest extends TestCase
{
    public string $api_url = 'https://lighthouse-tutorial.test/graphql';

    /** @test */
    public function it_fetch_a_user()
    {
        $request = Graphql::request($this->api_url);
        $query = QueryBuilder::query()
            ->user(['id' => 1], fn(Field $user) => $user->email()
                ->name()
                ->id()
            );
        $response = $request->get($query->toString());
        dd($response);
        $this->assertTrue(true);
    }
}
