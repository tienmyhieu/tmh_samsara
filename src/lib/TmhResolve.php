<?php

namespace lib;

class TmhResolve
{
//    private string $locale = '';
//    private string $route = '';
//    private string $subDomain = '';
//    private string $template = '';
//
//    public function initialize(TmhDatabase $database): void
//    {
//        $this->resolveRoute();
//        $this->resolveSubDomain();
//        $this->resolveLocale();
//        $this->resolveTemplate($database);
//    }
//
//    public function language(): string
//    {
//        return substr($this->locale, 0, 2);
//    }
//
//    public function locale(): string
//    {
//        return $this->locale;
//    }
//
//    public function template(): string
//    {
//        return $this->template;
//    }
//
//    private function isInvalidSubDomain(string $subDomain): bool
//    {
//        $invalidSubDomains = [TMH, 'www'];
//        return in_array($subDomain, $invalidSubDomains);
//    }
//
//    private function resolveLocale(): void
//    {
//        $validLocales = $this->validLocales();
//        $localeExists = in_array($this->subDomain, array_keys($validLocales));
//        $this->locale = $localeExists ? $validLocales[$this->subDomain] : DEFAULT_LOCALE;
//    }
//
//    private function resolveRoute(): void
//    {
//        parse_str($_SERVER['REDIRECT_QUERY_STRING'], $fields);
//        $this->route = $fields['title'];
//    }
//
//    private function resolveSubDomain(): void
//    {
//        $domain = $_SERVER['SERVER_NAME'];
//        $domainParts = explode('.', $domain);
//        $this->subDomain = $domainParts[0];
//    }
//
//    private function resolveTemplate(TmhDatabase $database): void
//    {
//        $locales = $database->entities('locale', []);
//        echo "<pre>";
//        print_r($locales);
//        echo "</pre>";
//        $isInvalidSubDomain = $this->isInvalidSubDomain($this->subDomain);
//        if ($isInvalidSubDomain) {
//            $this->template = DEFAULT_TEMPLATE;
//        } else if (empty($this->route)) {
//            $this->template = HOME_TEMPLATE;
//        } else {
//            // get from route
//        }
//    }
//
//    private function validLocales(): array
//    {
//        return [
//            'de' => 'de-DE',
//            'en' => 'en-GB',
//            'fr' => 'fr-FR',
//            'ja' => 'ja-JP',
//            'vi' => 'vi-VN',
//            'zh-hans' => 'zh-Hans',
//            'zh-hant' => 'zh-Hant'
//        ];
//    }
}
