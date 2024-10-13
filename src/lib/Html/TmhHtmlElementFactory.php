<?php

namespace lib\Html;

class TmhHtmlElementFactory
{
    private const string FAV_ICON = 'http://img1.tienmyhieu.com/favicon.png';
    private const string PRINT_STYLE_SHEET = 'http://cdn.tienmyhieu.com/css/tienmyhieu-print.css';
    private const string STYLE_SHEET = 'http://cdn.tienmyhieu.com/css/tienmyhieu.css';
    private TmhHtmlNodeFactory $nodeFactory;

    public function __construct(TmhHtmlNodeFactory $nodeFactory)
    {
        $this->nodeFactory = $nodeFactory;
    }

    public function ancestors(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_ancestors', $childNodes, '');
    }

    public function ancestorItem(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_ancestor_item', $childNodes, '');
    }

    public function ancestorItemLink(string $href, string $innerHtml, string $title): array
    {
        $attributes = ['class' => 'tmh_ancestor_item_link', 'href'=> $href, 'title' => $title];
        return $this->nodeFactory->a($attributes, [], $innerHtml);
    }

    public function article(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_article', $childNodes, '');
    }

    public function body(array $attributes, array $childNodes): array
    {
        return $this->nodeFactory->body($attributes, $childNodes);
    }

    public function br(): array
    {
        return $this->nodeFactory->br();
    }

    public function center(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_center', $childNodes, '');
    }

    public function charset(): array
    {
        return $this->nodeFactory->meta(['charset' => 'utf-8']);
    }

    public function citations(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_citations', $childNodes, '');
    }

    public function component(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_component', $childNodes, '');
    }

    public function componentGroup(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_component_group', $childNodes, '');
    }

    public function componentList(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_component_list', $childNodes, '');
    }

    public function contentBody(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_body', $childNodes, '');
    }

    public function creativeCommons(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_creative_commons', $childNodes, '');
    }

    public function creativeCommonsLink(string $href, string $innerHtml, string $title): array
    {
        $attributes = [
            'class' => 'tmh_creative_commons_link',
            'href' => $href,
            'title' => $title,
            'target' => '_blank'
        ];
        return $this->nodeFactory->a($attributes, [], $innerHtml);
    }

    public function entityList(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_entity_list', $childNodes, '');
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
            $this->printStyleSheet(),
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

    public function imageGroupList(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_image_group_list', $childNodes, '');
    }

    public function img(string $alt, string $src): array
    {
        return $this->nodeFactory->img(['alt' => $alt, 'class' => 'tmh_image', 'src' => $src]);
    }

    public function indentedSmallText(string $innerHtml): array
    {
        return $this->nodeFactory->span(['class' => 'tmh_indented_small_text'], $innerHtml);
    }

    public function indentedFartherSmallText(string $innerHtml): array
    {
        return $this->nodeFactory->span(['class' => 'tmh_indented_farther_small_text'], $innerHtml);
    }

    public function linkedImage(array $attributes): array
    {
        $attributes = array_merge($attributes, ['class' => 'tmh_list_item_link']);
        $linkAttributes = [
            'class' => 'tmh_list_item_link',
            'href' => $attributes['href'],
            'name' => $attributes['name'],
            'target' => $attributes['target'],
            'title' => $attributes['title']
        ];
        return $this->nodeFactory->a($linkAttributes, [$this->img($attributes['title'], $attributes['src'])], '');
    }

    public function listItem(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_list_item', $childNodes, '');
    }

    public function externalListItemLink(array $rawAttributes, string $innerHtml, array $childNodes): array
    {
        $attributes = [
            'class' => 'tmh_list_item_link',
            'href'=> $rawAttributes['href'],
            'title' => $rawAttributes['title'],
            'target' => '_blank'
        ];
        return $this->nodeFactory->a($attributes, $childNodes, $innerHtml);
    }

    public function listItemLink(array $rawAttributes, string $innerHtml): array
    {
        $attributes = [
            'class' => 'tmh_list_item_link',
            'href'=> $rawAttributes['href'],
            'title' => $rawAttributes['title']
        ];
        if (isset($rawAttributes['name'])) {
            $attributes['name'] = $rawAttributes['name'];
        }
        return $this->nodeFactory->a($attributes, [], $innerHtml);
    }

    public function listTitle(string $title): array
    {
        return $this->nodeFactory->span([], $title);
    }

    public function marginLeft(): array
    {
        return $this->nodeFactory->div('tmh_margin', [], '&nbsp;');
    }

    public function marginRight(): array
    {
        return $this->nodeFactory->div('tmh_margin', [], '&nbsp;');
    }

    public function maxims(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_maxims', $childNodes, '');
    }

    public function metaDescription(string $description): array
    {
        return $this->nodeFactory->meta(['name' => 'description', 'content' => $description]);
    }

    public function keyValueList(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_key_value_list', $childNodes, '');
    }

    public function keyValue(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_key_value', $childNodes, '');
    }

    public function keyValueKey(string $key): array
    {
        return $this->nodeFactory->span([], $key);
    }

    public function keyValueListTitle(string $title): array
    {
        return $this->nodeFactory->div('tmh_key_value_list_title', [], $title);
    }

    public function keyValueValue(array $attributes, string $innerHtml): array
    {
        return $this->nodeFactory->span($attributes, $innerHtml);
    }

    public function metaKeywords(string $keywords): array
    {
        return $this->nodeFactory->meta(['name' => 'keywords', 'content' => $keywords]);
    }

    public function metaViewport(): array
    {
        return $this->nodeFactory->meta(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0']);
    }

    public function p(array $childNodes): array
    {
        return $this->nodeFactory->p($childNodes);
    }

    public function pageTitle(string $title): array
    {
        return $this->nodeFactory->div('tmh_title', [], $title);
    }

    public function paleListItemLink(string $href, string $innerHtml, string $title): array
    {
        $attributes = ['class' => 'tmh_pale_list_item_link', 'href'=> $href, 'title' => $title];
        return $this->nodeFactory->a($attributes, [], $innerHtml);
    }

    public function paleText(string $innerHtml): array
    {
        return $this->nodeFactory->span(['class' => 'tmh_pale_text'], $innerHtml);
    }

    public function printStyleSheet(): array
    {
        return $this->nodeFactory->link(['media'=> 'print', 'rel' => 'stylesheet', 'href' => self::PRINT_STYLE_SHEET]);
    }

    public function quoteList(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_quote_list', $childNodes, '');
    }

    public function quoteListHorizontal(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_quote_list_horizontal', $childNodes, '');
    }

    public function verticalQuoteListItem(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_vertical_quote_list_item', $childNodes, '');
    }

    public function quoteListVertical(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_quote_list_vertical', $childNodes, '');
    }

    public function siblings(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_siblings', $childNodes, '');
    }

    public function siblingItem(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_sibling_item', $childNodes, '');
    }

    public function siblingItemLink(string $href, string $innerHtml, string $title): array
    {
        $attributes = ['class' => 'tmh_sibling_item_link', 'href'=> $href, 'title' => $title];
        return $this->nodeFactory->a($attributes, [], $innerHtml);
    }

    public function smallText(string $innerHtml): array
    {
        return $this->nodeFactory->span(['class' => 'tmh_small_text'], $innerHtml);
    }

    public function span(array $attributes, string $innerHtml): array
    {
        return $this->nodeFactory->span($attributes, $innerHtml);
    }

    public function styleSheet(): array
    {
        return $this->nodeFactory->link(['media'=> 'screen', 'rel' => 'stylesheet', 'href' => self::STYLE_SHEET]);
    }

    public function source(array $attributes): array
    {
        return $this->nodeFactory->source($attributes);
    }

    public function svgImg(string $src): array
    {
        return $this->nodeFactory->img(['alt' => '', 'class' => 'tmh_svg_icon', 'src' => $src]);
    }

    public function table(string $class, array $childNodes): array
    {
        return $this->nodeFactory->table($class, $childNodes);
    }

    public function td(array $attributes, string $innerHtml): array
    {
        return $this->nodeFactory->td($attributes, $innerHtml);
    }

    public function thinBr(): array
    {
        return $this->nodeFactory->div('tmh_thin_br', [], '');
    }

    public function title(string $title): array
    {
        return $this->nodeFactory->title($title);
    }

    public function tr(string $class, array $childNodes): array
    {
        return $this->nodeFactory->tr($class, $childNodes);
    }

    public function topic(string $class, string $title): array
    {
        return $this->nodeFactory->div('tmh_' . $class, [$this->nodeFactory->span([], $title)], '');
    }

    public function uploadGroup(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_upload_group', $childNodes, '');
    }

    public function uploadGroupList(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_upload_group_list', $childNodes, '');
    }

    public function video(string $height, string $src, string $width): array
    {
        $source = $this->source(['src' => $src, 'type' => 'video/mp4']);
        $video = $this->nodeFactory->video(['height' => $height, 'width' => $width], [$source], '');
        return $this->nodeFactory->div('tmh_video', [$video], '');
    }

    public function videoGroup(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_video_group', $childNodes, '');
    }

    public function videoGroupList(array $childNodes): array
    {
        return $this->nodeFactory->div('tmh_video_group_list', $childNodes, '');
    }
}
