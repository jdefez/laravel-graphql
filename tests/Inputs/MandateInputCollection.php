<?php

namespace Jdefez\LaravelGraphql\tests\Inputs;

use Jdefez\LaravelGraphql\Inputs\BaseInputCollection;
use Jdefez\LaravelGraphql\Inputs\InputableCollection;

class MandateInputCollection extends BaseInputCollection implements InputableCollection
{
    public function __construct(
        public string $name,
        public array $inputs,
    ) {
    }
}
