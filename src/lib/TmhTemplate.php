<?php

namespace lib;

readonly class TmhTemplate
{
//    private TmhContent $content;
//    private TmhResolve $resolve;
//    private array $localized;
//    private array $template;
//
//    public function initialize(
//        TmhContent $content,
//        TmhData $data,
//        TmhJson $json,
//        TmhResolve $resolve,
//        TmhTranslation $translation
//    ): void {
//        $this->localized = $data->localize($json, $translation, $resolve->template());
//        $this->template = $json->load(__DIR__ . '/../tmh_template/', $resolve->template());
//        $this->content = $content;
//        $this->resolve = $resolve;
//    }
//
//    public function toHtml(): string
//    {
//        return '<!DOCTYPE html>' . PHP_EOL . $this->nodes($this->template['childNodes']);
//    }
//
//    private function attributes(array $attributes): string
//    {
//        $html = '';
//        foreach ($attributes as $key => $value) {
//            $html .= ' ' . $key . '="' . $this->replace($value) . '"';
//        }
//        return $html;
//    }
//
//    private function childNodes(array $node, $eol=PHP_EOL): string
//    {
//        $closingHtml = $node['selfClosing'] ? '' : '>';
//        return count($node['childNodes']) ? '>' . $eol . $this->nodes($node['childNodes']) : $closingHtml;
//    }
//
//    private function closeNode(array $node): string
//    {
//        $eol = in_array($node['htmlTag'], ['a', 'img']) ? '' : PHP_EOL;
//        return ($node['selfClosing'] ? '/>' : '</' . $node['htmlTag']. '>') . $eol;
//    }
//
//    private function innerHtml(array $node): string
//    {
//        $eol = in_array($node['htmlTag'], ['a', 'img']) ? '' : PHP_EOL;
//        $hasInnerHtml =  strlen($node['innerHTML']) > 0;
//        return $hasInnerHtml ? '>' . $this->replace($node['innerHTML']) : $this->childNodes($node, $eol);
//    }
//
//    private function nodes(array $nodes): string
//    {
//        $html = '';
//        foreach ($nodes as $node) {
//            if ($node['htmlTag'] == 'database') {
//
//            } else {
//                $html .= $this->openNode($node);
//                $html .= $this->innerHtml($node);
//                $html .= $this->closeNode($node);
//            }
//        }
//        return $html;
//    }
//
//    private function openNode(array $node): string
//    {
//        return '<' . $node['htmlTag'] . $this->attributes($node['attributes']);
//    }
//
//    private function replace(string $value): string
//    {
//        $patterns = ["/__DOMAIN__/", "/__LANGUAGE__/", "/__LOCALE__/"];
//        $replacements = [
//            TMH,
//            strtolower($this->resolve->language()),
//            strtolower($this->resolve->locale()) . '-'
//        ];
//
//        if (preg_match('/(data)(\.)(.+)/', $value, $matches)) {
//            $value = $this->localized[$matches[3]];
//        }
//
//        return preg_replace($patterns, $replacements, $value);
//    }
}
