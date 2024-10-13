<?php

namespace lib;

class TmhElementFactory
{
    private const string FAV_ICON = 'http://img1.tienmyhieu.com/favicon.png';
    private const string STYLE_SHEET = 'http://cdn.tienmyhieu.com/css/tienmyhieu.css';
    private TmhNodeFactory $nodeFactory;

    public function __construct(TmhNodeFactory $nodeFactory)
    {
        $this->nodeFactory = $nodeFactory;
    }

    public function body(array $childNodes): array
    {
        return $this->nodeFactory->body($childNodes);
    }

    public function br(): array
    {
        return $this->nodeFactory->br();
    }

    public function center(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_center', $childNodes, '');
    }

    public function contentBody(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_body', $childNodes, '');
    }

    public function charset(): array
    {
       return $this->nodeFactory->meta(['charset' => 'utf-8']);
    }

    public function favIcon(): array
    {
        return $this->nodeFactory->link(['rel' => 'icon', 'href' => self::FAV_ICON, 'type' => 'image/png']);
    }

    public function head(string $description, string $keywords, string $title): array
    {
        return $this->nodeFactory->head([
            $this->charset(),
            $this->title($title),
            $this->metaDescription($description),
            $this->metaKeywords($keywords),
            $this->metaViewport(),
            $this->styleSheet(),
            $this->favIcon()
        ]);
    }

    public function html(array $childNodes, string $language): array
    {
        return $this->nodeFactory->html(['lang' => $language], $childNodes);
    }

    public function imageGroup(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_image_group', $childNodes, '');
    }

    public function img(string $alt, string $src): array
    {
        return $this->nodeFactory->img(['alt' => $alt, 'class' => 'tmh_image', 'src' => $src]);
    }

    public function linkedImage(string $href, string $src, string $title): array
    {
        $attributes = ['class' => 'tmh_list_item_link', 'href' => $href, 'title' => $title];
        return $this->nodeFactory->a($attributes, [$this->img($title, $src)], '');
    }

    public function listItem(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_list_item', $childNodes, '');
    }

    public function listItemLink(string $href, string $innerHtml, string $title): array
    {
        $attributes = ['class' => 'tmh_list_item_link', 'href'=> $href, 'title' => $title];
        return $this->nodeFactory->a($attributes, [], $innerHtml);
    }

    public function listTitle(string $title): array
    {
        return $this->nodeFactory->span($title);
    }

    public function marginLeft(): array
    {
        return $this->nodeFactory->div('tmh_margin', [], '&nbsp;');
    }

    public function marginRight(): array
    {
        return $this->nodeFactory->div('tmh_margin', [], '&nbsp;');
    }

    public function metaDescription(string $description): array
    {
        return $this->nodeFactory->meta(['name' => 'description', 'content' => $description]);
    }

    public function metaKeywords(string $keywords): array
    {
        return $this->nodeFactory->meta(['name' => 'keywords', 'content' => $keywords]);
    }

    public function metaViewport(): array
    {
        return $this->nodeFactory->meta(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0']);
    }

    public function pageTitle(string $title): array
    {
        return $this->nodeFactory->div('tmh_title', [], $title);
    }

    public function sectionTitle(string $title): array
    {
        return $this->nodeFactory->div('tmh_section', [$this->nodeFactory->span($title)], '');
    }

    public function span(string $innerHtml): array
    {
        return $this->nodeFactory->span($innerHtml);
    }

    public function styleSheet(): array
    {
        return $this->nodeFactory->link(['rel' => 'stylesheet', 'href' => self::STYLE_SHEET]);
    }

    public function subSection(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_sub_section', $childNodes, '');
    }

    public function subSections(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_sub_sections', $childNodes, '');
    }

    public function title(string $title): array
    {
        return $this->nodeFactory->title($title);
    }
}