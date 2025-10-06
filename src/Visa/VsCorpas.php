<?php

namespace PaulWarrenTT\Moneris\Visa;

class VsCorpas
{
    private $template = [
        'conjunction_ticket_number' => null,
        'trip_leg_info' => null,
        'control_id' => null,
    ];

    private $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function getData()
    {
        return $this->data;
    }

    public function setCorpas($conjunction_ticket_number, vsTripLegInfo $vsTripLegInfo, $control_id): void
    {
        $this->template['conjunction_ticket_number'] = $conjunction_ticket_number;
        $this->template['trip_leg_info'] = $vsTripLegInfo->getData();
        $this->template['control_id'] = $control_id;

        $this->data[] = $this->template;
    }
}//end class

