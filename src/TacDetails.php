<?php

namespace PaulWarrenTT\Moneris;

class TacDetails
{
    // Properties
    public string $text;
    public string $url;
    public string $version;
    public string $languageCode;

    // Methods

    function getLanguageCode(): string
    {
        return $this->languageCode;
    }

    function setLanguageCode(string $languageCode): void
    {
        $this->languageCode = $languageCode;
    }

    function getText(): string
    {
        return $this->text;
    }

    function setText($text): void
    {
        $this->text = $text;
    }

    function getUrl(): string
    {
        return $this->url;
    }

    function setUrl($url): void
    {
        $this->url = $url;
    }

    function getVersion(): string
    {
        return $this->version;
    }

    function setVersion($version): void
    {
        $this->version = $version;
    }
}
