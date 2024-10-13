<?php

namespace lib\HtmlComponent;

use lib\Html\TmhHtmlElementFactory;

require_once('TmhHtmlComponent.php');
require_once('TmhAncestorsHtmlComponent.php');
require_once('TmhArticleHtmlComponent.php');
require_once('TmhCitationListHtmlComponent.php');
require_once('TmhCreativeCommonsHtmlComponent.php');
require_once('TmhEntityListHtmlComponent.php');
require_once('TmhExternalEntityListHtmlComponent.php');
require_once('TmhFlatEntityListHtmlComponent.php');
require_once('TmhFlatUploadListHtmlComponent.php');
require_once('TmhHorizontalQuoteListHtmlComponent.php');
require_once('TmhImageGroupListHtmlComponent.php');
require_once('TmhKeyValueListHtmlComponent.php');
require_once('TmhMaximListHtmlComponent.php');
require_once('TmhMeasurementListHtmlComponent.php');
require_once('TmhVerticalQuoteListHtmlComponent.php');
require_once('TmhSiblingsHtmlComponent.php');
require_once('TmhTitleHtmlComponent.php');
require_once('TmhTopicHtmlComponent.php');
require_once('TmhUploadGroupHtmlComponent.php');
require_once('TmhUploadGroupListHtmlComponent.php');
require_once('TmhVideoGroupListHtmlComponent.php');

class TmhHtmlComponentFactory
{
    private TmhHtmlElementFactory $elementFactory;

    public function __construct(TmhHtmlElementFactory $elementFactory)
    {
        $this->elementFactory = $elementFactory;
    }

    public function create(string $type): TmhHtmlComponent
    {
        return match($type) {
            'ancestors' => new TmhAncestorsHtmlComponent($this->elementFactory),
            'article' => new TmhArticleHtmlComponent($this->elementFactory),
            'citation_list' => new TmhCitationListHtmlComponent($this->elementFactory),
            'creative_commons' => new TmhCreativeCommonsHtmlComponent($this->elementFactory),
            'entity_list' => new TmhEntityListHtmlComponent($this->elementFactory),
            'external_entity_list' => new TmhExternalEntityListHtmlComponent($this->elementFactory),
            'flat_entity_list' => new TmhFlatEntityListHtmlComponent($this->elementFactory),
            'flat_upload_list' => new TmhFlatUploadListHtmlComponent($this->elementFactory),
            'horizontal_quote_list' => new TmhHorizontalQuoteListHtmlComponent($this->elementFactory),
            'image_group_list' => new TmhImageGroupListHtmlComponent($this->elementFactory),
            'key_value_list' => new TmhKeyValueListHtmlComponent($this->elementFactory),
            'maxim_list' => new TmhMaximListHtmlComponent($this->elementFactory),
            'measurement_list' => new TmhMeasurementListHtmlComponent($this->elementFactory),
            'vertical_quote_list' => new TmhVerticalQuoteListHtmlComponent($this->elementFactory),
            'siblings' => new TmhSiblingsHtmlComponent($this->elementFactory),
            'numbered_topic',
            'topic_no_print',
            'topic' => new TmhTopicHtmlComponent($this->elementFactory),
            'upload_group' => new TmhUploadGroupHtmlComponent($this->elementFactory),
            'upload_group_list' => new TmhUploadGroupListHtmlComponent($this->elementFactory),
            'video_group_list' => new TmhVideoGroupListHtmlComponent($this->elementFactory),
            'default_title', 'title' => new TmhTitleHtmlComponent($this->elementFactory)
        };
    }
}
