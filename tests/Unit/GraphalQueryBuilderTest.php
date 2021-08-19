<?php

namespace Jdefez\Graphql\Tests;

use Jdefez\Graphql\Facades\Graphql;
use Jdefez\Graphql\QueryBuilder;
use Jdefez\Graphql\tests\TestCase;

class GraphqlQueryBuilderTest extends TestCase
{
    /** @test */
    public function dumy_test()
    {
        return $this->assertInstanceOf(QueryBuilder::class, Graphql::query());
    }
}
