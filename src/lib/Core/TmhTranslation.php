<?php

namespace lib\Core;

class TmhTranslation
{
    private const string DEFAULT_LOCALE = 'vi-VN';
    private string $locale;
    private array $translations;

    public function __construct(TmhJson $json)
    {
        $this->translations = $json->translations();
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function locales(): array
    {
        return array_keys($this->translations);
    }

    public function initializeLocale(array $locales): void
    {
        $domain = $_SERVER['SERVER_NAME'];
        $domainParts = explode('.', $domain);
        $isValidLocale = in_array($domainParts[0], array_keys($locales));
        $this->locale = $isValidLocale ? $locales[$domainParts[0]]['name'] : self::DEFAULT_LOCALE;
    }

    public function inscription(string $uuid): string
    {
        $isValidLocale = in_array($uuid, array_keys($this->translations['zh-Hant']));
        return $isValidLocale ? $this->translations['zh-Hant'][$uuid] : $uuid;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function translate(string $key): string
    {
        $translations = $this->translations[$this->locale];
        return in_array($key, array_keys($translations)) ? $translations[$key] : $key;
    }

    public function translateMany(array $keys): array
    {
        $translated = [];
        foreach ($keys as $key) {
            $translated[] = $this->translate($key);
        }
        return $translated;
    }
}
