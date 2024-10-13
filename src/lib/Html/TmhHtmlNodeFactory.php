<?php

namespace lib\Html;

class TmhHtmlNodeFactory
{
    public function a(array $attributes, array $childNodes, string $innerHtml): array
    {
        return $this->node('a', $attributes, $childNodes, $innerHtml, false);
    }

    public function body(array $childNodes): array
    {
        return $this->node('body', [], $childNodes, '', false);
    }

    public function boldSpan(string $innerHtml): array
    {
        return $this->node('span', ['class' => 'tmh_bold'], [], $innerHtml, false);
    }

    public function br(): array
    {
        return $this->node('br', [], [], '', true);
    }

    public function div(string $class, array $childNodes, string $innerHtml): array
    {
        return $this->node('div', ['class' => $class], $childNodes, $innerHtml, false);
    }

    public function head(array $childNodes): array
    {
        return $this->node('head', [], $childNodes, '', false);
    }

    public function html(array $attributes, array $childNodes): array
    {
        return $this->node('html', $attributes, $childNodes, '', false);
    }

    public function img(array $attributes): array
    {
        return $this->node('img', $attributes, [], '', true);
    }

    public function link(array $attributes): array
    {
        return $this->node('link', $attributes, [], '', true);
    }

    public function meta(array $attributes): array
    {
        return $this->node('meta', $attributes, [], '', true);
    }

    public function span(string $class, string $innerHtml): array
    {
        $attributes = [];
        if (0 < strlen($class)) {
            $attributes['class'] = $class;
        }
        return $this->node('span', $attributes, [], $innerHtml, false);
    }

    public function title(string $innerHtml): array
    {
        return $this->node('title', [], [], $innerHtml, false);
    }

    private function node(string $tag, array $attributes, array $children, string $innerHtml, bool $selfClosing): array
    {
        return [
            'htmlTag' => $tag,
            'attributes' => $attributes,
            'childNodes' => $children,
            'innerHTML' => $innerHtml,
            'selfClosing' => $selfClosing
        ];
    }
}
