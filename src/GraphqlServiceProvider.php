<?php

namespace Jdefez\LaravelGraphql;

use Illuminate\Support\ServiceProvider;

class GraphqlServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->app->bind(Graphqlable::class, Graphql::class);
    }
}
