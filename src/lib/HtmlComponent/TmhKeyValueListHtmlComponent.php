<?php

namespace lib\HtmlComponent;

use lib\Html\TmhHtmlElementFactory;

class TmhKeyValueListHtmlComponent implements TmhHtmlComponent
{
    private TmhHtmlElementFactory $elementFactory;

    public function __construct(TmhHtmlElementFactory $elementFactory)
    {
        $this->elementFactory = $elementFactory;
    }

    public function get(array $entity): array
    {
        $componentNodes = [$this->elementFactory->keyValueListTitle($entity['translation'])];
        foreach ($entity['list']['items'] as $keyValue) {
            $key = $this->elementFactory->keyValueKey($keyValue['key']);
            $spacer = $this->elementFactory->span([], ': ');
            if ($keyValue['type'] == 'route') {
                $route = $keyValue['value'];
                $value = $this->elementFactory->listItemLink(
                    ['href' => $route['href'], 'title' => $route['title']],
                    $route['innerHtml']
                );
            } else {
                $attributes = [];
                if (isset($keyValue['lang']) && 0 < strlen($keyValue['lang'])) {
                    $attributes['lang'] = $keyValue['lang'];
                }
                $value = $this->elementFactory->keyValueValue($attributes, $keyValue['value']);
            }
            $componentNodes[] = $this->elementFactory->keyValue([$key, $spacer, $value]);
        }
        return [$this->elementFactory->keyValueList($componentNodes)];
    }
}
