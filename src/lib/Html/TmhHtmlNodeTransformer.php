<?php

namespace lib\Html;

class TmhHtmlNodeTransformer
{
    public function toHtml(array $nodes): string
    {
        return '<!DOCTYPE html>' . PHP_EOL . $this->nodes([$nodes]);
    }

    private function attributes(array $attributes): string
    {
        $html = '';
        foreach ($attributes as $key => $value) {
            $html .= ' ' . $key . '="' . $value . '"';
        }
        return $html;
    }

    private function childNodes(array $node, $eol = PHP_EOL): string
    {
        $endTag = '>';
        $closingHtml = $node['selfClosing'] ? '' : $endTag;
        if ($node['htmlTag'] == 'video') {
            $endTag = ' controls' . $endTag;
        }
        return count($node['childNodes']) ? $endTag . $eol . $this->nodes($node['childNodes']) : $closingHtml;
    }

    private function closeNode(array $node): string
    {
        $eol = in_array($node['htmlTag'], ['a', 'img']) ? '' : PHP_EOL;
        return ($node['selfClosing'] ? '/>' : '</' . $node['htmlTag']. '>') . $eol;
    }

    private function innerHtml(array $node): string
    {
        $eol = in_array($node['htmlTag'], ['a', 'img']) ? '' : PHP_EOL;
        $hasInnerHtml = strlen($node['innerHTML']) > 0;
        return $hasInnerHtml ? '>' . $node['innerHTML'] : $this->childNodes($node, $eol);
    }

    private function nodes(array $nodes): string
    {
        $html = '';
        foreach ($nodes as $node) {
            $html .= $this->openNode($node);
            $html .= $this->innerHtml($node);
            $html .= $this->closeNode($node);
        }
        return $html;
    }

    private function openNode($element): string
    {
        return '<' . $element['htmlTag'] . $this->attributes($element['attributes']);
    }
}
