<?php

namespace lib;

class TmhSamsara
{
    private array $database;
    private string $locale;
    private string $route;
    private array $routes;
    private array $template;
    private array $translations;

    public function __construct(TmhJson $json)
    {
        $this->database = $json->database();
        $this->locale = $this->currentLocale();
        $this->route = $this->currentRoute();
        $this->translations = $json->translations();
        $this->routes = $this->translateRoutes($json->routes());
        $this->template = $json->template($this->templatePath());
    }

    private function attributes(array $attributes): string
    {
        $html = '';
        foreach ($attributes as $key => $value) {
            $value = is_array($value) ? $this->replaceMany($value) : $this->replace($value);
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

    public function currentEntity(): array
    {
        $routes = $this->currentRoutes();
        return in_array($this->route, array_keys($routes)) ? $routes[$this->route] : $routes[''];
    }

    public function currentLocale(): string
    {
        $domain = $_SERVER['SERVER_NAME'];
        $domainParts = explode('.', $domain);
        $locales = $this->database['locale'];
        $isValidLocale = in_array($domainParts[0], array_keys($locales));

        return $isValidLocale ? $locales[$domainParts[0]]['name'] : 'vi-VN';
    }

    public function currentRoute(): string
    {
        parse_str($_SERVER['REDIRECT_QUERY_STRING'], $fields);
        return $fields['title'];
    }

    public function currentRoutes(): array
    {
        return $this->routes[$this->currentLocale()];
    }
//
//    public function emperorCoins(array $filters, bool $byInscriptionType = false): array
//    {
//        $emperorCoins = $this->filterEntity('emperor_coin', $filters);
//        if ($byInscriptionType) {
//            $transformed = [];
//            $noSpacesLocales = ['ja-JP', 'zh-Hans', 'zh-Hant'];
//            $noSpaces = in_array($this->locale, $noSpacesLocales);
//            $separator = $noSpaces ? '' : ' ';
//            foreach ($emperorCoins as $primaryKey => $attributes) {
//                $parts = explode($separator, $attributes['translation']);
//                if (count($parts) === 8) {
//                    $isRepeating = ($parts[2] == $parts[3]) && ($parts[4] == $parts[5]);
//                    if ($isRepeating) {
//                        echo "<pre>" . '4r' . PHP_EOL . "</pre>";
//                    } else {
//                        echo "<pre>" . '8' . PHP_EOL . "</pre>";
//                    }
//                } else {
//                    echo "<pre>" . '4' . PHP_EOL . "</pre>";
//                }
//            }
//        }
//        return $emperorCoins;
//    }

    public function filterEntity(string $entity, array $filters): array
    {
        $filteredEntities = [];
        $entities = $this->database[$entity];
        $keys = array_keys($filters);
        $values = array_values($filters);
        $filter = implode('.', $values);
        foreach ($entities as $primaryKey => $attributes) {
            $entityFilter = '';
            foreach ($attributes as $key => $value) {
                if (in_array($key, $keys)) {
                    $entityFilter .= $value . '.';
                }
            }
            $entityFilter = substr($entityFilter, 0, -1);
            if ($filter === $entityFilter && $attributes['active'] === '1') {
                $translation = $this->translations[$this->locale][$attributes['translation']];
                $attributes['translation'] = $translation;
                $filteredEntities[$primaryKey] = $attributes;
            }
        }
        return $filteredEntities;
    }

    private function findRoute(string $primaryKey): array
    {
        foreach ($this->routes[$this->locale] as $route) {
            if ($route['id'] == $primaryKey) {
                return $route;
            }
        }
        return $this->routes[$this->locale][''];
    }

    private function innerHtml(array $node): string
    {
        $eol = in_array($node['htmlTag'], ['a', 'img']) ? '' : PHP_EOL;
        $innerHtml = '';
        if (is_array($node['innerHTML'])) {
            $replaced = [];
            foreach ($node['innerHTML'] as $toReplace) {
                $replaced[] = $this->replace($toReplace);
            }
            $innerHtml = implode(' ', $replaced);
        } else {
            if (strlen($node['innerHTML']) > 0) {
                $innerHtml = $this->replace($node['innerHTML']);
            }
        }
        $hasInnerHtml = strlen($innerHtml) > 0;
        return $hasInnerHtml ? '>' . $innerHtml : $this->childNodes($node, $eol);
    }

    public function memoryUsage(): string
    {
        $unit= ['b','kb','mb','gb','tb','pb'];
        $size = memory_get_usage();
        return @round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    public function nodes(array $nodes): string
    {
        $html = '';
        foreach ($nodes as $node) {
            if ($node['htmlTag'] == 'route') {
                $html .= $this->routeNode($node['attributes']);
            } else {
                $html .= $this->openNode($node);
                $html .= $this->innerHtml($node);
                $html .= $this->closeNode($node);
            }
        }
        return $html;
    }

    public function openNode($element): string
    {
        return '<' . $element['htmlTag'] . $this->attributes($element['attributes']);
    }

    private function replace(string $value): string
    {
        if (preg_match('/(locale)(\.)(.+)/', $value, $matches)) {
            $value = $this->translate($matches[3], $this->locale);
        }
        return $value;
    }

    private function replaceMany(array $values): string
    {
        $replaced = [];
        foreach ($values as $value) {
            $replaced[] = $this->replace($value);
        }
        return implode(' ', $replaced);
    }

    public function routeNode(array $attributes): string
    {
        $route = $this->findRoute($attributes['id']);
        $node = [
            'htmlTag' => 'a',
            'attributes' => [
                'class' => $attributes['class'],
                'href' =>  $route['href'],
                'title' => $route['title']
            ],
            'innerHTML' => $route['innerHtml'],
            'selfClosing' => false
        ];
        return $this->nodes([$node]);
    }

    public function templatePath(): string
    {
        $currentEntity = $this->currentEntity();
        $directory = $currentEntity['template_dir'] ? $currentEntity['template_dir'] . '/' : '';
        return $directory . $currentEntity['id'];
    }

    public function toHtml(): string
    {
        if (empty($this->template)) {
            return '';
        }
        return '<!DOCTYPE html>' . PHP_EOL . $this->nodes($this->template['childNodes']);
    }

    public function translate(string $key, string $locale): string
    {
        $translations = $this->translations[$locale];
        return in_array($key, array_keys($translations)) ? $translations[$key] : $key;
    }

    public function translateEntity(string $entity, string $primaryKey): string
    {
        $translations = $this->translations[$this->currentLocale()];
        return $translations[$this->database[$entity][$primaryKey]['translation']];
    }

    public function translateMany(array $keys, string $locale): array
    {
        $translated = [];
        foreach ($keys as $key) {
            $translated[] = $this->translate($key, $locale);
        }
        return $translated;
    }

    public function translateRoutes(array $routes):  array
    {
        $translated = [];
        foreach (array_keys($this->translations) as $locale) {
            foreach ($routes as $primaryKey => $route) {
                unset($route['active']);
                unset($route['comment']);
                $href = '';
                foreach ($route['href'] as $hrefPart) {
                    $href .= str_replace(' ', '_', $this->translate($hrefPart, $locale)) . '/';
                }
                $href = substr($href, 0, -1);
                $route['href'] = '/' . $href;
                $route['id'] = $primaryKey;
                $route['innerHtml'] = $this->translate($route['innerHtml'], $locale);
                $route['title'] = implode(' ', $this->translateMany($route['title'], $locale));
                $translated[$locale][$href] = $route;
            }
        }

        return $translated;
    }
}