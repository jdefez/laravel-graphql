<?php

namespace Jdefez\LaravelGraphql\Inputs;

abstract class BaseInputCollection implements InputableCollection
{
    /**
     * @var array<Inputable> $inputs
     */
    public function __construct(
        public string $name,
        public array $inputs
    ) {
    }

    public function add(Inputable ...$inputs): void
    {
        foreach ($inputs as $input) {
            $this->inputs[] = $input;
        }
    }

    public function isEmpty(): bool
    {
        return empty($this->inputs);
    }

    public function toArray(): array
    {
        $output = [];

        foreach ($this->inputs as $input) {
            $output[] = $input->toArray();
        }

        return $output;
    }
}

