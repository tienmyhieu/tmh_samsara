<?php

namespace lib;

class TmhSamsara
{
    private string $currentHtmlAttribute = '';
    private string $currentHtmlTag = '';
    private array $dynamicNodes = [];
    private string $locale = '';
    private array $localeGroups = [];
    private string $route = '';
    private string $subDomain = '';
    private array $template = [];
    private string $templateId = '';
    private array $translations = [];

    public function __construct(readonly private TmhDatabase $database, readonly private TmhJson $json)
    {
        $this->localeGroups = $this->database->entities('locale_group', []);
        $this->loadAllTranslations();
        $this->resolveRoute();
        $this->resolveSubDomain();
        $this->resolveLocale();
        $this->resolveTemplateId();
        $this->loadDynamicNodes();
        $this->loadTemplate();
    }

    private function isInvalidSubDomain(string $subDomain): bool
    {
        return in_array($subDomain, [TMH, 'www']);
    }

    private function loadAllTranslations(): void
    {
        foreach ($this->database->entities('locale', []) as $locale) {
            $this->translations[$locale['name']] = $this->loadLocale($locale['name']);
        }
    }

    private function loadDynamicNodes(): void
    {
        $this->dynamicNodes = $this->database->entities('dynamic_node', []);
    }

    private function loadTemplate(): void
    {
        $this->template = $this->json->load(__DIR__ . '/../tmh_template/', $this->templateId);
    }

    private function resolveLocale(): void
    {
        $validLocales = $this->validLocales();
        $localeExists = in_array($this->subDomain, array_keys($validLocales));
        if (!$this->isInvalidSubDomain($this->subDomain)) {
            $this->locale = $localeExists ? $validLocales[$this->subDomain]['name'] : DEFAULT_LOCALE;
        } else {
            $this->locale = 'en-GB';
        }
    }

    private function resolveRoute(): void
    {
        parse_str($_SERVER['REDIRECT_QUERY_STRING'], $fields);
        $this->route = $fields['title'];
    }

    private function resolveSubDomain(): void
    {
        $domain = $_SERVER['SERVER_NAME'];
        $domainParts = explode('.', $domain);
        $this->subDomain = $domainParts[0];
    }

    private function resolveTemplateId(): void
    {
        $isInvalidSubDomain = $this->isInvalidSubDomain($this->subDomain);
        if ($isInvalidSubDomain) {
            $this->templateId = DEFAULT_TEMPLATE;
        } else if (empty($this->route)) {
            $this->templateId = HOME_TEMPLATE;
        } else {
            // get from route
        }
    }

    private function validLocales(): array
    {
        return $this->toDomainLocales($this->database->entities('locale', []));
    }

    public function toHtml(): string
    {
        return '<!DOCTYPE html>' . PHP_EOL . $this->nodes($this->template['childNodes']);
    }

    private function attributes(array $attributes): string
    {
        $html = '';
        foreach ($attributes as $key => $value) {
            $this->currentHtmlAttribute = $key;
            $html .= ' ' . $key . '="' . $this->replace($value) . '"';
        }
        return $html;
    }

    private function childNodes(array $node, $eol=PHP_EOL): string
    {
        $closingHtml = $node['selfClosing'] ? '' : '>';
        return count($node['childNodes']) ? '>' . $eol . $this->nodes($node['childNodes']) : $closingHtml;
    }

    private function closeNode(array $node): string
    {
        $eol = in_array($node['htmlTag'], ['a', 'img']) ? '' : PHP_EOL;
        return ($node['selfClosing'] ? '/>' : '</' . $node['htmlTag']. '>') . $eol;
    }

    private function innerHtml(array $node): string
    {
        $eol = in_array($node['htmlTag'], ['a', 'img']) ? '' : PHP_EOL;
        $hasInnerHtml =  strlen($node['innerHTML']) > 0;
        return $hasInnerHtml ? '>' . $this->replace($node['innerHTML']) : $this->childNodes($node, $eol);
    }

    private function databaseNodes(array $attributes): array
    {
        $entities = $this->database->entities($attributes['entity'], $attributes['query']);
        return match($attributes['entity']) {
            'entity' => $this->transformEntityList($entities, $attributes['parent_node']),
            'locale' => $this->transformLocaleList($entities, $attributes['parent_node'])
        };
    }

    private function transformEntityList(array $entities, string $parentNode): array
    {
        $transformed = [];
        $locales = $this->translations[$this->locale];
        foreach ($entities as $primaryKey => $attributes) {
            if ($attributes['expand'] === '1') {
                $expandedEntities = $this->database->entities($attributes['name'], []);
                foreach ($expandedEntities as $expandedPrimaryKey => $expandedAttributes)
                {
                    $title = $expandedAttributes['name'];
                    if (in_array('translation', array_keys($expandedAttributes)) && !empty($this->locale)) {
                        $translation = $expandedAttributes['translation'];
                        $translationExists = in_array($translation, array_keys($locales));
                        $title = $translationExists ? $locales[$translation] : $title;
                        $href = '/' . str_replace(' ', '_', $title);
                        $a = $this->transformListLink($href, $title, $title);
                        $transformed[] = $this->localeParentNode([$a], $parentNode);
                    }
                }
            } else {
                $title = $attributes['name'];
                if (in_array('translation', array_keys($attributes)) && !empty($this->locale)) {
                    $translation = $attributes['translation'];
                    $translationExists = in_array($translation, array_keys($locales));
                    $title = $translationExists ? $locales[$translation] : $title;
                    $href = '/' . str_replace(' ', '_', $title);
                    $a = $this->transformListLink($href, $title, $title);
                    $transformed[] = $this->localeParentNode([$a], $parentNode);
                }
            }
        }

        return $transformed;
    }

    private function nodes(array $nodes): string
    {
        $html = '';
        foreach ($nodes as $node) {
            if ($node['htmlTag'] == 'database') {
                $databaseNodes = $this->nodes($this->databaseNodes($node['attributes']));;
                $html .= $databaseNodes;
            } else {
                $html .= $this->openNode($node);
                $html .= $this->innerHtml($node);
                $html .= $this->closeNode($node);
            }
        }
        return $html;
    }

    public function getLocale(string $key): string
    {
        $translations = $this->translations[$this->locale];
        return in_array($key, array_keys($translations)) ? $translations[$key] : $key;
    }

    public function getManyLocales(array $keys): array
    {
        $many = [];
        foreach ($keys as $key) {
            $many[] = $this->getLocale($key);
        }
        return $many;
    }

    private function loadLocale(string $locale): array
    {
        $allLocales = [];
        $files = scandir(__DIR__ . '/../tmh_locales/' . $locale);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $fileName = str_replace('.json', '', $file);
            $locales = $this->json->load(__DIR__ . '/../tmh_locales/' . $locale . '/', $fileName);
            foreach ($locales as $key => $value){
                $allLocales[$key] = $value;
            }
        }
        return $allLocales;
    }

    private function openNode(array $node): string
    {
        $this->currentHtmlTag = $node['htmlTag'];
        return '<' . $node['htmlTag'] . $this->attributes($node['attributes']);
    }

    private function replace(string $value): string
    {
        $patterns = ["/__DOMAIN__/", "/__LANGUAGE__/", "/__LOCALE__/"];
        $replacements = [
            TMH,
            strtolower(substr($this->locale, 0, 2)),
            strtolower($this->locale) . '-'
        ];

        if (preg_match('/(locale_group)(\.)(.+)/', $value, $matches)) {
            $separator = ' ';
            if ($this->currentHtmlAttribute == 'keywords' && $this->currentHtmlTag == 'meta') {
                $separator = ', ';
            }
            $localeGroup = $this->localeGroups[$matches[3]];
            $value = implode($separator, $this->getManyLocales($localeGroup['locales']));
        }

        return preg_replace($patterns, $replacements, $value);
    }

    private function localeParentNode(array $childNodes, string $parentNode): array
    {
        $parentNode = $this->dynamicNodes[$parentNode];
        $parentNode['childNodes'] = $childNodes;
        return $parentNode;
    }

    private function transformListLink(string $href, string $innerHtml, string $title): array
    {
        $listLink = $this->dynamicNodes['xl8lgfar'];
        $listLink['attributes']['href'] = $href;
        $listLink['attributes']['title'] = $title;
        $listLink['innerHTML'] = $innerHtml;
        return $listLink;
    }

    private function transformLocaleList(array $entities, string $parentNode): array
    {
        $i = 0;
        $transformed = [];
        $excludeCurrent = $parentNode === 'o3c4tyhp';
        $localeEntities = $this->toDomainLocales($entities);
        $locales = $this->translations[$this->locale];
        foreach ($localeEntities as $primaryKey => $attributes) {
            if ($excludeCurrent && $this->locale == $attributes['name']) {
                $i++;
                continue;
            }
            $href = TMH_PROTOCOL . '://' . strtolower($primaryKey) . '.' . TMH . TMH_TLD;
            $title = $attributes['native_name'];
            if (in_array('translation', array_keys($attributes)) && !empty($this->locale)) {
                $translation = $attributes['translation'];
                $translationExists = in_array($translation, array_keys($locales));
                $title = $translationExists ? $locales[$translation] : $title;
            }
            $a = $this->transformListLink($href, $attributes['native_name'], $title);
            $transformed[] = $this->localeParentNode([$a], $parentNode);
            if ($excludeCurrent) {
                $separatorNode = $this->dynamicNodes[$parentNode];
                $separatorNode['innerHTML'] = '&#9676;';
                if ($i < count($localeEntities) - 1) {
                    $transformed[] = $separatorNode;
                }
            }
            $i++;
        }
        return $transformed;
    }

    private function toDomainLocales(array $locales): array
    {
        $transformed = [];
        foreach ($locales as $locale) {
            if ($locale['language']['name'] == 'zh') {
                $transformed[strtolower($locale['name'])] = $locale;
            } else {
                $transformed[$locale['language']['name']] = $locale;
            }
        }
        return $transformed;
    }
}
