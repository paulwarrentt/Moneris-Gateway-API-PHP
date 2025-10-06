<?php

namespace PaulWarrenTT\Moneris\MasterCard;

class McTax
{
    private array $template = [
        'tax_amount' => null,
        'tax_rate' => null,
        'tax_type' => null,
        'tax_id' => null,
        'tax_included_in_sales' => null,
    ];

    private array $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setTax($tax_amount, $tax_rate, $tax_type, $tax_id, $tax_included_in_sales): void
    {
        $this->template['tax_amount'] = $tax_amount;
        $this->template['tax_rate'] = $tax_rate;
        $this->template['tax_type'] = $tax_type;
        $this->template['tax_id'] = $tax_id;
        $this->template['tax_included_in_sales'] = $tax_included_in_sales;

        $this->data[] = $this->template;
    }
}
