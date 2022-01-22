<?php

namespace Jdefez\LaravelGraphql\tests\Inputs;

use Jdefez\LaravelGraphql\Inputs\BaseInput;
use Jdefez\LaravelGraphql\Inputs\Inputable;

class CommiteeUserInput extends BaseInput implements Inputable
{
    public function __construct(
        public int $commitee_id,
        public ?string $matricule = null,
        public ?string $role = null,
    ) {
    }

    public function toArray(): array
    {
        // filter null properties

        $attributes = array_filter([
            'role' => $this->role,
            'matricule' => $this->matricule,
            'commitee_id' => $this->commitee_id,
        ]);
        
        return parent::relationsToArray($attributes);
    }
}
