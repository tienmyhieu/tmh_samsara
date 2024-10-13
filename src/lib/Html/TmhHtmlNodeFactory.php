<?php

namespace lib\Html;

class TmhHtmlNodeFactory
{
    public function a(array $attributes, array $childNodes, string $innerHtml): array
    {
        return $this->node('a', $attributes, $childNodes, $innerHtml, false);
    }

    public function body(array $attributes, array $childNodes): array
    {
        return $this->node('body', $attributes, $childNodes, '', false);
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

    public function p(array $childNodes): array
    {
        return $this->node('p', ['class' => 'tmh_paragraph'], $childNodes, '', false);
    }

    public function span(array $attributes, string $innerHtml): array
    {
//        $attributes = [];
//        if (0 < strlen($class)) {
//            $attributes['class'] = $class;
//        }
        return $this->node('span', $attributes, [], $innerHtml, false);
    }

    public function source(array $attributes): array
    {
        return $this->node('source', $attributes, [], '', true);
    }

    public function table(string $class, array $childNodes): array
    {
        return $this->node('table', ['class' => $class], $childNodes, '', false);
    }

    public function td(array $attributes, string $innerHtml): array
    {
        return $this->node('td', $attributes, [], $innerHtml, false);
    }

    public function title(string $innerHtml): array
    {
        return $this->node('title', [], [], $innerHtml, false);
    }

    public function tr(string $class, array $childNodes): array
    {
        return $this->node('tr', ['class' => $class], $childNodes, '', false);
    }

    public function video(array $attributes, array $childNodes, string $innerHtml): array
    {
        return $this->node('video', $attributes, $childNodes, $innerHtml, false);
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
