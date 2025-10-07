<?php

namespace PaulWarrenTT\Moneris;

class Tac
{
    // Properties
    public ?array $tacDetailsList;

    // Methods
    function getTacDetailsList(): array
    {
        return $this->tacDetailsList;
    }

    function setTacs(array $tacs): void
    {
        $this->tacDetailsList = $tacs;
    }

    function getTacCount(): int
    {
        return count($this->tacDetailsList);
    }
}
