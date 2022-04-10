<?php

namespace Jdefez\LaravelGraphql\tests\Inputs;

use Jdefez\LaravelGraphql\Inputs\BaseInput;
use Jdefez\LaravelGraphql\Inputs\Inputable;

class MandateDefinitionInput extends BaseInput implements Inputable
{
    public function __construct(
        public string $label,
        public int $credit,
        public ?int $id = null,
    ) {
    }
}
