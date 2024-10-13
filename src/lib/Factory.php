<?php

namespace lib;

use lib\Attribute\TmhAttributeFactory;
use lib\Core\TmhDatabase;
use lib\Core\TmhEntity;
use lib\Core\TmhEntityAttribute;
use lib\Core\TmhEntityMetadata;
use lib\Core\TmhJson;
use lib\Core\TmhRoute;
use lib\Core\TmhTranslation;
use lib\Html\TmhHtmlDocumentFactory;
use lib\Html\TmhHtmlElementFactory;
use lib\Html\TmhHtmlNodeFactory;
use lib\Html\TmhHtmlNodeTransformer;
use lib\HtmlComponent\TmhHtmlComponentFactory;

require_once('Attribute/TmhAttributeFactory.php');
require_once('Core/TmhDatabase.php');
require_once('Core/TmhEntity.php');
require_once('Core/TmhEntityAttribute.php');
require_once('Core/TmhEntityMetadata.php');
require_once('Core/TmhJson.php');
require_once('Core/TmhRoute.php');
require_once('Core/TmhTranslation.php');
require_once('Html/TmhHtmlDocumentFactory.php');
require_once('Html/TmhHtmlElementFactory.php');
require_once('Html/TmhHtmlNodeFactory.php');
require_once('Html/TmhHtmlNodeTransformer.php');
require_once('HtmlComponent/TmhHtmlComponentFactory.php');
require_once('TmhSamsara.php');

class Factory
{
    private TmhDatabase $database;
    private TmhEntityAttribute $entityAttribute;
    private TmhHtmlElementFactory $htmlElementFactory;
    private TmhJson $json;
    private TmhRoute $route;
    private TmhTranslation $translation;

    public function __construct()
    {
        $this->htmlElementFactory = $this->htmlElementFactory();
        $this->json = $this->json();
        $this->translation = $this->translation();
        $this->database = $this->database();
        $this->route = $this->route();
        $this->entityAttribute = $this->entityAttribute();
    }

    public function attributeFactory(): TmhAttributeFactory
    {
        return new TmhAttributeFactory($this->database, $this->entityAttribute, $this->route);
    }

    public function controller(): TmhSamsara
    {
        return new TmhSamsara($this->htmlDocumentFactory(), $this->entity());
    }

    public function database(): TmhDatabase
    {
        return new TmhDatabase($this->json, $this->translation);
    }

    public function entity(): TmhEntity
    {
        return new TmhEntity($this->attributeFactory(), $this->entityMetadata(), $this->entityAttribute);
    }

    public function entityAttribute(): TmhEntityAttribute
    {
        return new TmhEntityAttribute($this->json, $this->translation);
    }

    public function entityMetadata(): TmhEntityMetadata
    {
        return new TmhEntityMetadata($this->route);
    }

    public function htmlComponentFactory(): TmhHtmlComponentFactory
    {
        return new TmhHtmlComponentFactory($this->htmlElementFactory);
    }

    public function htmlDocumentFactory(): TmhHtmlDocumentFactory
    {
        return new TmhHtmlDocumentFactory(
            $this->htmlElementFactory,
            $this->htmlComponentFactory(),
            $this->htmlNodeTransformer()
        );
    }

    public function htmlElementFactory(): TmhHtmlElementFactory
    {
        return new TmhHtmlElementFactory($this->htmlNodeFactory());
    }

    public function htmlNodeFactory(): TmhHtmlNodeFactory
    {
        return new TmhHtmlNodeFactory();
    }

    public function htmlNodeTransformer(): TmhHtmlNodeTransformer
    {
        return new TmhHtmlNodeTransformer();
    }

    public function json(): TmhJson
    {
        return new TmhJson();
    }

    public function route(): TmhRoute
    {
        return new TmhRoute($this->json, $this->translation);
    }

    public function translation(): TmhTranslation
    {
        return new TmhTranslation($this->json);
    }
}