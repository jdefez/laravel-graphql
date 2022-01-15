<?php

namespace Jdefez\LaravelGraphql\Inputs;

interface InputableCollection
{
    public function toArray(): array;

    public function isEmpty(): bool;

    public function add(Inputable ...$inputs): void;
}
