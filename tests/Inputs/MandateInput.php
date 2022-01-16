<?php

namespace Jdefez\LaravelGraphql\tests\Inputs;

use Jdefez\LaravelGraphql\Inputs\BaseInput;
use Jdefez\LaravelGraphql\Inputs\Inputable;

class MandateInput extends BaseInput implements Inputable
{
    public function __construct(
        public int $mandate_definition_id,
        public string $label,
        public int $credit,
        public ?int $id = null,
    ) {
    }

    public function toArray(): array
    {
        return parent::relationsToArray([
            'mandate_definition_id' => $this->mandate_definition_id,
            'label' => $this->label,
            'credit' => $this->credit,
            'id' => $this->id,
        ]);
    }
}
