<?php

namespace Jdefez\LaravelGraphql\Tests\Inputs;

use Jdefez\LaravelGraphql\Inputs\BaseInput;
use Jdefez\LaravelGraphql\Inputs\Inputable;

class CommiteeUserInput extends BaseInput implements Inputable
{
    protected bool $toArrayStrategy = self::EXCLUDE_NULL_PROPERTIES;

    public function __construct(
        public int $commitee_id,
        public ?string $matricule = null,
        public ?string $role = null,
    ) {
    }
}
