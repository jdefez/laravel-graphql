<?php

namespace Jdefez\Graphql;

class Field extends Node
{
    public static function setName(string $name): Node
    {
        return new self($name);
    }
}
