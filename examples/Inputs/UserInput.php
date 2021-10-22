<?php

namespace Jdefez\LaravelGraphql\examples\Inputs;

use Jdefez\LaravelGraphql\Inputs\BaseInput;
use Jdefez\LaravelGraphql\Inputs\Inputable;


class UserInput extends BaseInput implements Inputable
{
    public function __construct(
        public string $firstname,
        public string $lastname,
        public string $email
    ) {
    }

    public function toArray(): array
    {
        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
        ];
    }
}
