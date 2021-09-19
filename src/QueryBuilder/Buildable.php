<?php

namespace Jdefez\LaravelGraphql\QueryBuilder;

interface Buildable
{
    public static function mutation(?array $arguments = null): Builder;

    public static function query(): Builder;

    public function toString(bool $ugglify = true): string;

    public function dump(): string;
}
