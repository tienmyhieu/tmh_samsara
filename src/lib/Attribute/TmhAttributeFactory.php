<?php

namespace lib\Attribute;

use lib\Core\TmhDatabase;
use lib\Core\TmhEntityAttribute;
use lib\Core\TmhRoute;

require_once('TmhAttribute.php');
require_once('TmhAncestorsAttribute.php');
require_once('TmhCitationListAttribute.php');
require_once('TmhCreativeCommonsAttribute.php');
require_once('TmhDefaultTitleAttribute.php');
require_once('TmhDefaultTitleUploadGroupAttribute.php');
require_once('TmhEntityListAttribute.php');
require_once('TmhImageGroupAttribute.php');
require_once('TmhImageGroupListAttribute.php');
require_once('TmhTextGroupAttribute.php');
require_once('TmhKeyValueListAttribute.php');
require_once('TmhMaximListAttribute.php');
require_once('TmhNumberedPaleTextAttribute.php');
require_once('TmhNumberedShadowRouteAttribute.php');
require_once('TmhNumberedTextAttribute.php');
require_once('TmhNumberedTopicAttribute.php');
require_once('TmhPaleTextAttribute.php');
require_once('TmhQuoteAttribute.php');
require_once('TmhRouteAttribute.php');
require_once('TmhRoutedImageGroupAttribute.php');
require_once('TmhShadowRouteAttribute.php');
require_once('TmhSiblingsAttribute.php');
require_once('TmhTextAttribute.php');
require_once('TmhTitleAttribute.php');
require_once('TmhTopicAttribute.php');
require_once('TmhTranslatedQuoteAttribute.php');
require_once('TmhUploadGroupAttribute.php');
require_once('TmhUploadGroupListAttribute.php');
require_once('TmhVerticalQuoteListAttribute.php');

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
            'citation_list' => new TmhCitationListAttribute($this->entityAttribute),
            'creative_commons' => new TmhCreativeCommonsAttribute($this->entityAttribute),
            'default_title' => new TmhDefaultTitleAttribute(),
            'default_title_upload_group' => new TmhDefaultTitleUploadGroupAttribute($this->database),
            'entity_list' => new TmhEntityListAttribute($this, $this->entityAttribute),
            'dated_identified_image_group',
            'identified_image_group',
            'text_group_image_group' => new TmhImageGroupAttribute($this, $this->database),
            'image_group_list' => new TmhImageGroupListAttribute($this, $this->entityAttribute),
            'key_value_list' => new TmhKeyValueListAttribute($this->route, $this->entityAttribute),
            'maxim_list' => new TmhMaximListAttribute($this->entityAttribute),
            'numbered_pale_text' => new TmhNumberedPaleTextAttribute(),
            'numbered_shadow_route' => new TmhNumberedShadowRouteAttribute($this->route),
            'numbered_text' => new TmhNumberedTextAttribute(),
            'numbered_topic' => new TmhNumberedTopicAttribute(),
            'pale_text' => new TmhPaleTextAttribute(),
            'quote' => new TmhQuoteAttribute(),
            'route' => new TmhRouteAttribute($this->route),
            'typed_routed_image_group',
            'routed_image_group' => new TmhRoutedImageGroupAttribute($this->database, $this->entityAttribute, $this->route),
            'pale_shadow_route',
            'shadow_route' => new TmhShadowRouteAttribute($this->route),
            'siblings' => new TmhSiblingsAttribute($this->database, $this->route),
            'text' => new TmhTextAttribute(),
            'text_group' => new TmhTextGroupAttribute($this->entityAttribute),
            'topic' => new TmhTopicAttribute(),
            'translated_quote' => new TmhTranslatedQuoteAttribute(),
            'upload_group' => new TmhUploadGroupAttribute($this->database),
            'upload_group_list' => new TmhUploadGroupListAttribute($this, $this->entityAttribute),
            'vertical_quote_list' => new TmhVerticalQuoteListAttribute($this, $this->entityAttribute),
            default => new TmhTitleAttribute()
        };
    }
}
