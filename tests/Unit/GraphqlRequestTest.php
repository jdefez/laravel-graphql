<?php

namespace Jdefez\Graphql\Tests;

use Jdefez\Graphql\Facades\Graphql;
use Jdefez\Graphql\Field;
use Jdefez\Graphql\QueryBuilder;
use Jdefez\Graphql\Requestable;
use Jdefez\Graphql\tests\TestCase;

class GraphqlRequestTest extends TestCase
{
    public string $api_url = 'https://lighthouse-tutorial.test/graphql';

    /** @test */
    public function it_can_instanciate_a_request()
    {
        return $this->assertInstanceOf(
            Requestable::class,
            Graphql::request($this->api_url)
        );
    }

    /** @test */
    public function it_can_send_a_request()
    {
        $request = Graphql::request($this->api_url);
        $query = QueryBuilder::query()
            ->user(['id' => 1], fn(Field $user) => $user->email()
                ->name()
                ->id()
            );
        $response = $request->get($query->toString());
        dd($response);

        $this->assertEquals(
            'QUERY { user(id: 1) { email name id }}',
            $query->toString()
        );
    }
}
