<?php

namespace lib;

class TmhSamsara
{
    private array $currentEntity;
    private array $database;
    private TmhDocumentFactory $documentFactory;
    private array $entity;
    private string $locale;
    private TmhNodeTransformer $nodeTransformer;
    private string $route;
    private array $routeMap;
    private array $routes;
    private array $translations;

    public function __construct(TmhDocumentFactory $documentFactory, TmhJson $json, TmhNodeTransformer $nodeTransformer)
    {
        $this->documentFactory = $documentFactory;
        $this->nodeTransformer = $nodeTransformer;

        $this->database = $json->database();
        $this->locale = $this->currentLocale();
        $this->route = $this->currentRoute();
        $this->translations = $json->translations();
        $this->routes = $this->translateRoutes($json->routes());
        $this->currentEntity = $this->currentEntity();
        $this->setSections();
    }

    private function setSections(): void
    {
        $uuid = $this->currentEntity['uuid'];
        $this->entity = [];
        $sections = [];
        $rawSections = $this->filterEntities($this->database['entity_section'], 'entity', $uuid);
        foreach ($rawSections as $sectionUuid => $section) {
            $lists = $this->filterEntities($this->database['section_list'], 'section', $section['section']);
            $sectionTitle = $section['translation'];
            if (0 < strlen($sectionTitle)) {
                $section['translation'] = $this->translate($sectionTitle, $this->locale);
            }
            foreach ($lists as $listUuid => $list) {
                $listTitle = $this->database['list'][$list['list']]['translation'];
                if (0 < strlen($listTitle)) {
                    $listTitle = $this->translate($listTitle, $this->locale);
                }
                $list['translation'] = $listTitle;
                $entities = [];
                $rawEntities = $this->filterEntities($this->database['list_entity'], 'list', $list['list']);
                foreach ($rawEntities as $entityUuid => $rawEntity) {
                    if ($rawEntity['type'] == 'route') {
                        $hasShadowRoute = 0 < strlen($rawEntity['translation']);
                        $route = $this->routes[$this->locale][$rawEntity['entity']];
                        if ($hasShadowRoute) {
                            $route['innerHtml'] = $this->translate($rawEntity['translation'], $this->locale);
                        }
                        $route['innerHtml'] = str_replace('_', ' ', $route['innerHtml']);
                        $route['identifier'] = $rawEntity['identifier'];
                        $route['entity_type'] = $rawEntity['type'];
                        unset($route['active']);
                        unset($route['entity']);
                        //unset($route['identifier']);
                        unset($route['uuid']);
                        $entities[$entityUuid] = $route;
                    }
                    if ($rawEntity['type'] == 'image_group') {
                        $imageGroup = $this->database['image_group'][$rawEntity['entity']];
                        $translation = $this->translate($imageGroup['translation'], $this->locale);
                        $title = str_replace('||identifier||', '', $translation);
                        $imageGroup['translation'] = $title . ' ' . $imageGroup['identifier'];
                        $route = $this->routes[$this->locale][$imageGroup['route']];
                        unset($route['active']);
                        unset($route['entity']);
                        unset($route['identifier']);
                        unset($route['uuid']);
                        $imageGroup['route'] = $route;
                        $imageGroup['entity_type'] = $rawEntity['type'];
                        unset($imageGroup['active']);
                        unset($imageGroup['identifier']);
                        unset($imageGroup['type']);
                        $tmpImages = [];
                        foreach ($imageGroup['images'] as $image) {
                            $tmpImages[] = 'http://img1.tienmyhieu.com/images/256/' . $image . '.jpg';
                        }
                        $imageGroup['images'] = $tmpImages;
                        $entities[$entityUuid] = $imageGroup;
                    }
                    if ($rawEntity['type'] == 'entity_citation') {
                        $entityCitation = $this->database['entity_citation'][$rawEntity['entity']];
                        $citation = $this->database['citation'][$entityCitation['citation']];
                        $translation = $this->translate($citation['translation'], $this->locale);
                        $translation = str_replace('||page||', $entityCitation['page'], $translation);
                        $translation = str_replace('||plate||', $entityCitation['plate'], $translation);
                        $entities[$entityUuid] = ['entity_type' => $rawEntity['type'], 'translation' => $translation];
                    }
                    if ($rawEntity['type'] == 'text') {
                        $translation = $this->translate($rawEntity['translation'], $this->locale);
                        $entities[$entityUuid] = [
                            'entity_type' => $rawEntity['type'],
                            'translation' => $translation,
                            'identifier' => $rawEntity['identifier']
                        ];
                    }
                }
                $list['entities'] = $entities;
                unset($list['active']);
                unset($list['list']);
                unset($list['section']);
                $section['lists'][$listUuid] = $list;
                unset($section['active']);
                unset($section['entity']);
                unset($section['section']);
                $sections[$sectionUuid] = $section;
            }
        }
        $this->entity['sections'] = $sections;
    }

    public function currentEntity(): array
    {
        $routes = $this->currentRoutes();
        $routeMap = $this->routeMap[$this->locale];
        $routeExists = in_array($this->route, array_keys($routeMap));
        return $routeExists ? $routes[$routeMap[$this->route]] : $routes[''];
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
        return $this->routes[$this->locale];
    }

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

    public function memoryUsage(): string
    {
        $unit= ['b','kb','mb','gb','tb','pb'];
        $size = memory_get_usage();
        return @round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    private function filterEntities(array $entities, string $key, string $value): array
    {
        return array_filter($entities, function($entity) use($key, $value) {
            $hasKey = in_array($key, array_keys($entity));
            return !$hasKey || $entity[$key] == $value;
        });
    }

    public function setEntityMetadata(): void
    {
        $this->currentEntity['lang'] = substr($this->locale, 0, 2);
        $entityDescription = $this->currentEntity['title'];
        $entityKeywords = $this->currentEntity['title'];
        $this->currentEntity['pageTitle'] = $this->currentEntity['title'];
        $this->currentEntity['documentTitle'] = $this->currentEntity['title'];
        $href = substr($this->currentEntity['href'], 1);
        if (0 < strlen($href)) {
            $parts = explode('/', str_replace('_', ' ', $href));
            $entityDescription = implode(' ', $parts);
            $entityKeywords = implode(', ', $parts);
            $defaultTitle = $this->translate('nn3zskng', $this->locale);
            $this->currentEntity['pageTitle'] = $defaultTitle;
            if ($this->currentEntity['entity'] == 'book') {
                $this->currentEntity['pageTitle'] = $parts[1];
            }
            if ($this->currentEntity['entity'] == 'emperor_metal') {
                $this->currentEntity['pageTitle'] = $parts[1];
            }
            if (1 == count($parts)) {
                $this->currentEntity['documentTitle'] = $defaultTitle . ' ' . $this->currentEntity['title'];
            }
        }
        $this->currentEntity['description'] = $entityDescription;
        $this->currentEntity['keywords'] = $entityKeywords;
    }

    public function toHtml(): string
    {
        $this->setEntityMetadata();
        $this->currentEntity['sections'] = $this->entity['sections'];
        $document = $this->documentFactory->create($this->currentEntity);
        return '<!DOCTYPE html>' . PHP_EOL . $this->nodeTransformer->nodes([$document]);
    }

    public function translate(string $key, string $locale): string
    {
        $translations = $this->translations[$locale];
        return in_array($key, array_keys($translations)) ? $translations[$key] : $key;
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
                    $translation = $this->translate($hrefPart, $locale);
                    $translation = $this->translateIdentifier($translation, $route['identifier']);
                    $href .= str_replace(' ', '_', $translation) . '/';
                }
                $href = substr($href, 0, -1);
                $route['href'] = '/' . $href;
                $route['uuid'] = $primaryKey;
                $innerHtml = $this->translate($route['innerHtml'], $locale);
                $route['innerHtml'] = $this->translateIdentifier($innerHtml, $route['identifier']);
                $route['title'] = implode(' ', $this->translateMany($route['title'], $locale));
                $translated[$locale][$primaryKey] = $route;
                $this->routeMap[$locale][$href] = $primaryKey;
            }
        }

        return $translated;
    }

    public function translateIdentifier(string $translation, string $identifier): string
    {
        return str_replace('||identifier||', '_' . $identifier, $translation);
    }
}