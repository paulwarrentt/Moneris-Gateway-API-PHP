<?php

namespace PaulWarrenTT\Moneris\Traits;

trait ToXML
{
    protected function toXML_low(array $dataArray, string $root): string
    {
        $xmlRoot = "";

        foreach ($dataArray as $key => $value) {
            if ( ! is_numeric($key) && $value != "" && $value != null) {
                $xmlRoot .= "<$key>";
            } elseif (is_numeric($key) && $key != "0") {
                $xmlRoot .= "</$root><$root>";
            }

            if (is_array($value)) {
                $xmlRoot .= $this->toXML_low($value, $key);
            } else {
                $xmlRoot .= $value;
            }

            if ( ! is_numeric($key) && $value != "" && $value != null) {
                $xmlRoot .= "</$key>";
            }
        }

        return $xmlRoot;
    }
}
