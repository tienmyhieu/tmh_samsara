<?php

namespace lib\Component;

use lib\TmhDatabase;
use lib\TmhRoute;
use lib\TmhStructure;

require_once('TmhComponent.php');
require_once('TmhAncestorsComponent.php');
require_once('TmhCitationListComponent.php');
require_once('TmhCreativeCommonsComponent.php');
require_once('TmhDefaultTitleComponent.php');
require_once('TmhEntityListComponent.php');
require_once('TmhImageGroupComponent.php');
require_once('TmhImageGroupListComponent.php');
require_once('TmhTextGroupComponent.php');
require_once('TmhKeyValueListComponent.php');
require_once('TmhNumberedPaleTextComponent.php');
require_once('TmhNumberedShadowRouteComponent.php');
require_once('TmhNumberedTextComponent.php');
require_once('TmhPaleTextComponent.php');
require_once('TmhQuoteListComponent.php');
require_once('TmhRouteComponent.php');
require_once('TmhRoutedImageGroupComponent.php');
require_once('TmhShadowRouteComponent.php');
require_once('TmhSiblingsComponent.php');
require_once('TmhTextComponent.php');
require_once('TmhTitleComponent.php');
require_once('TmhTopicComponent.php');
require_once('TmhUploadGroupComponent.php');
require_once('TmhUploadGroupListComponent.php');

class TmhComponentFactory
{
    private TmhDatabase $database;
    private TmhStructure $structure;
    private TmhRoute $route;

    public function __construct(TmhDatabase $database, TmhStructure $structure, TmhRoute $route)
    {
        $this->database = $database;
        $this->structure = $structure;
        $this->route = $route;
    }

    public function create(string $type): TmhComponent
    {
        return match($type) {
            'ancestors' => new TmhAncestorsComponent($this->route),
            'citation_list' => new TmhCitationListComponent($this->structure),
            'creative_commons' => new TmhCreativeCommonsComponent($this->structure),
            'default_title' => new TmhDefaultTitleComponent(),
            'entity_list' => new TmhEntityListComponent($this, $this->structure),
            'image_group' => new TmhImageGroupComponent($this->database, $this->structure),
            'image_group_list' => new TmhImageGroupListComponent($this, $this->structure),
            'key_value_list' => new TmhKeyValueListComponent($this->route, $this->structure),
            'numbered_pale_text' => new TmhNumberedPaleTextComponent(),
            'numbered_shadow_route' => new TmhNumberedShadowRouteComponent($this->route),
            'numbered_text' => new TmhNumberedTextComponent(),
            'pale_text' => new TmhPaleTextComponent(),
            'quote_list' => new TmhQuoteListComponent($this->database, $this->structure),
            'route' => new TmhRouteComponent($this->route),
            'routed_image_group' => new TmhRoutedImageGroupComponent($this->database, $this->route, $this->structure),
            'shadow_route' => new TmhShadowRouteComponent($this->route),
            'siblings' => new TmhSiblingsComponent($this->database, $this->route),
            'text' => new TmhTextComponent(),
            'text_group' => new TmhTextGroupComponent($this->structure),
            'topic' => new TmhTopicComponent(),
            'upload_group' => new TmhUploadGroupComponent($this->database),
            'upload_group_list' => new TmhUploadGroupListComponent($this, $this->structure),
            default => new TmhTitleComponent()
        };
    }
}
