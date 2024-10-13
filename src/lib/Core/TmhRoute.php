<?php

namespace lib\Core;

class TmhRoute
{
    private array $ancestors;
    private array $currentEntity;
    private string $locale;
    private string $requestedRoute;
    private array $routes;
    private array $routeMap;
    private TmhTranslation $translation;

    public function __construct(TmhJson $json, TmhTranslation $translation)
    {
        $this->translation = $translation;
        $this->locale = $this->translation->getLocale();
        $this->routes = $this->translateRoutes($json->routes());
        $this->setRequestedRoute();
        $this->setCurrentEntity();
        $this->setAncestors();
    }

    public function ancestors(): array
    {
        return $this->ancestors;
    }

    public function currentEntity(): array
    {
        return $this->currentEntity;
    }

    public function siblings(array $locales): array
    {
        $siblings = [];
        $uuid = $this->currentEntity['uuid'];
        foreach ($locales as $locale) {
            $subDomain = substr($locale['name'], 0, 2);
            if ($subDomain == 'zh') {
                $subDomain = strtolower($locale['name']);
            }
            $host = $_SERVER['REQUEST_SCHEME'] . '://' . $subDomain . '.' . 'tmh5.com';
            if ($locale['name'] != $this->locale) {
                $sibling = $this->routes[$locale['name']][$uuid];
                $sibling['innerHtml'] = $locale['native_name'];
                $translation = $this->translation->translate($locale['translation']);;
                $sibling['title'] = $translation . ' - ' . $sibling['title'];
                $sibling['href'] = $host . $sibling['href'];
                $siblings[$locale['name']] = $sibling;
            }
        }
        return $siblings;
    }

    public function routeEntityByKey(string $route): array
    {
        $routes = $this->routes[$this->locale];
        $routeExists = in_array($route, array_keys($routes));
        return $routeExists ? $routes[$route] : $routes[''];
    }

    public function routeEntity(string $route): array
    {
        $routes = $this->routes[$this->locale];
        $routeMap = $this->routeMap[$this->locale];
        $routeExists = in_array($route, array_keys($routeMap));
        return $routeExists ? $routes[$routeMap[$route]] : $routes[''];
    }

    public function setCurrentEntity(): void
    {
        $this->currentEntity = $this->routeEntity($this->requestedRoute);
    }

    private function setAncestors(): void
    {
        $host = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        $this->ancestors = [];
        $href = substr($this->currentEntity['href'], 1);
        if (0 < strlen($href)) {
            $home = $this->routeEntityByKey('umd0xr1h');
            $home['href'] = $host . $home['href'];
            $this->ancestors[] = $home;
            $parts = explode('/', $href);
            $cumulativeRoutes = [];
            $cumulative = '';
            foreach ($parts as $part) {
                $cumulative .= $part . '/';
                $cumulativeRoutes[] = substr($cumulative, 0, -1);
            }
            foreach ($cumulativeRoutes as $cumulativeRoute) {
                $this->ancestors[] = $this->routeEntity($cumulativeRoute);
            }
        }
    }

    private function setRequestedRoute(): void
    {
        parse_str($_SERVER['REDIRECT_QUERY_STRING'], $fields);
        $this->requestedRoute = $fields['title'];
    }

    private function translateRoutes(array $routes): array
    {
        $translated = [];
        $patterns = ["'", ' ', '、', '-', '.', "'"];
        $replacements = ['', '_', '', '_', '_', ''];
        $tmpLocale = $this->locale;
        foreach ($this->translation->locales() as $locale) {
            //$this->locale = $locale;
            $this->translation->setLocale($locale);
            foreach ($routes as $primaryKey => $route) {
                unset($route['active']);
                unset($route['comment']);
                $href = '';
                foreach ($route['href'] as $hrefPart) {
                    $translation = $this->translation->translate($hrefPart);
                    $translation = $this->translateIdentifier($translation, $route['identifier']);
                    $href .= str_replace($patterns, $replacements, $translation) . '/';
                }
                $route['defaultTitle'] = $this->translation->translate('nn3zskng');
                $route['lang'] = substr($this->translation->getLocale(), 0, 2);
                $href = substr($href, 0, -1);
                $route['href'] = '/' . $href;
                $route['uuid'] = $primaryKey;
                $innerHtml = $this->translation->translate($route['innerHtml']);
                $route['innerHtml'] = $this->translateIdentifier($innerHtml, $route['identifier']);
                $title = '';
                foreach ($route['title'] as $titlePart) {
                    $translation = $this->translation->translate($titlePart);
                    $translation = $this->translateIdentifier($translation, $route['identifier']);
                    $title .= str_replace('_', ' ', $translation) . ' ';
                }
                $route['title'] = $title;
                $translated[$locale][$primaryKey] = $route;
                $this->routeMap[$locale][$href] = $primaryKey;
            }
        }
        $this->translation->setLocale($tmpLocale);
        return $translated;
    }

    private function translateIdentifier(string $translation, string $identifier): string
    {
        return str_replace('||identifier||', '_' . $identifier, $translation);
    }
}
