<?php

namespace lib\Attribute;

use lib\Core\TmhDatabase;
use lib\Core\TmhEntityAttribute;
use lib\Core\TmhRoute;

require_once('TmhAttribute.php');
require_once('TmhAncestorsAttribute.php');
require_once('TmhArticleAttribute.php');
require_once('TmhCitationListAttribute.php');
require_once('TmhCreativeCommonsAttribute.php');
require_once('TmhDefaultTitleAttribute.php');
require_once('TmhEntityListAttribute.php');
require_once('TmhExternalEntityListAttribute.php');
require_once('TmhFlatUploadListAttribute.php');
require_once('TmhImageGroupAttribute.php');
require_once('TmhImageGroupListAttribute.php');
require_once('TmhTextGroupAttribute.php');
require_once('TmhKeyValueListAttribute.php');
require_once('TmhMaximListAttribute.php');
require_once('TmhMeasurementListAttribute.php');
require_once('TmhNumberedPaleTextAttribute.php');
require_once('TmhNumberedShadowRouteAttribute.php');
require_once('TmhNumberedTextAttribute.php');
require_once('TmhNumberedTopicAttribute.php');
require_once('TmhPaleTextAttribute.php');
require_once('TmhParagraphAttribute.php');
require_once('TmhQuoteAttribute.php');
require_once('TmhRouteAttribute.php');
require_once('TmhRoutedImageGroupAttribute.php');
require_once('TmhShadowRouteAttribute.php');
require_once('TmhSiblingsAttribute.php');
require_once('TmhTableAttribute.php');
require_once('TmhTextAttribute.php');
require_once('TmhTitleAttribute.php');
require_once('TmhTopicAttribute.php');
require_once('TmhTranslatedQuoteAttribute.php');
require_once('TmhUploadGroupAttribute.php');
require_once('TmhUploadGroupListAttribute.php');
require_once('TmhQuoteListAttribute.php');
require_once('TmhVideoGroupAttribute.php');
require_once('TmhVideoGroupListAttribute.php');

class TmhAttributeFactory
{
    private TmhDatabase $database;
    private TmhEntityAttribute $entityAttribute;
    private TmhRoute $route;

    public function __construct(TmhDatabase $database, TmhEntityAttribute $entityAttribute, TmhRoute $route)
    {
        $this->database = $database;
        $this->entityAttribute = $entityAttribute;
        $this->route = $route;
    }

    public function create(string $type): TmhAttribute
    {
        return match($type) {
            'ancestors' => new TmhAncestorsAttribute($this->route),
            'article' => new TmhArticleAttribute($this, $this->entityAttribute),
            'citation_list' => new TmhCitationListAttribute($this->entityAttribute),
            'creative_commons' => new TmhCreativeCommonsAttribute($this->entityAttribute),
            'default_title' => new TmhDefaultTitleAttribute(),
            'flat_entity_list',
            'entity_list' => new TmhEntityListAttribute($this, $this->entityAttribute),
            'external_entity_list' => new TmhExternalEntityListAttribute($this->database, $this->entityAttribute),
            'dated_identified_image_group',
            'identified_image_group',
            'text_group_above_image_group',
            'text_group_below_image_group',
            'translated_image_group',
            'untitled_image_group' => new TmhImageGroupAttribute($this, $this->database),
            'flat_upload_list' => new TmhFlatUploadListAttribute($this->database, $this->entityAttribute),
            'image_group_list' => new TmhImageGroupListAttribute($this, $this->entityAttribute),
            'key_value_list' => new TmhKeyValueListAttribute($this->route, $this->entityAttribute),
            'maxim_list' => new TmhMaximListAttribute($this->entityAttribute),
            'measurement_list' => new TmhMeasurementListAttribute($this->entityAttribute),
            'numbered_pale_text' => new TmhNumberedPaleTextAttribute(),
            'numbered_shadow_route',
            'pale_numbered_shadow_route' => new TmhNumberedShadowRouteAttribute($this->route),
            'numbered_text' => new TmhNumberedTextAttribute(),
            'numbered_topic' => new TmhNumberedTopicAttribute(),
            'pale_text' => new TmhPaleTextAttribute(),
            'paragraph' => new TmhParagraphAttribute($this->entityAttribute),
            'quote' => new TmhQuoteAttribute($this->entityAttribute),
            'route' => new TmhRouteAttribute($this->route),
            'identified_routed_image_group',
            'typed_routed_image_group',
            'routed_image_group' => new TmhRoutedImageGroupAttribute($this->database, $this->entityAttribute, $this->route),
            'pale_shadow_route',
            'shadow_route' => new TmhShadowRouteAttribute($this->route),
            'siblings' => new TmhSiblingsAttribute($this->database, $this->route),
            'table' => new TmhTableAttribute($this->entityAttribute),
            'text' => new TmhTextAttribute(),
            'text_group' => new TmhTextGroupAttribute($this->entityAttribute),
            'topic_no_print',
            'topic' => new TmhTopicAttribute(),
            'translated_quote' => new TmhTranslatedQuoteAttribute(),
            'upload_group' => new TmhUploadGroupAttribute($this->database),
            'upload_group_list' => new TmhUploadGroupListAttribute($this, $this->entityAttribute),
            'horizontal_quote_list',
            'vertical_quote_list' => new TmhQuoteListAttribute($this, $this->entityAttribute),
            'video_group' => new TmhVideoGroupAttribute($this->database),
            'video_group_list' => new TmhVideoGroupListAttribute($this, $this->entityAttribute),
            default => new TmhTitleAttribute()
        };
    }
}
