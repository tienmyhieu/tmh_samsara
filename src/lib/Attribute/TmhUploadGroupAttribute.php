<?php

namespace lib\Attribute;

use lib\Core\TmhDatabase;

class TmhUploadGroupAttribute implements TmhAttribute
{
    private const string UPLOAD_BASE_URL = 'http://img1.tienmyhieu.com/uploads/';
    private TmhDatabase $database;

    public function __construct(TmhDatabase $database)
    {
        $this->database = $database;
    }

    public function create(array $entity): array
    {
        $rawUploadGroup = $this->database->entity('upload_group', $entity['entity']);
        $uploads = [];
        foreach ($rawUploadGroup['uploads'] as $rawUpload) {
            $upload = $this->database->entity('upload', $rawUpload);
            $uploads[] = [
                'alt' => str_replace('||identifier||', ' ' . $upload['identifier'], $upload['alt']),
                'name' => 'uplo_' . $upload['identifier'],
                'src' => self::UPLOAD_BASE_URL . '128/' . $upload['src'] . '.jpg',
                'title' => str_replace('||identifier||', ' ' . $upload['identifier'], $upload['alt']),
                'type' => $upload['type'],
                'href' => $this->webSiteUrl($upload)
            ];
        }
        return [
            'component_type' => $entity['type'],
            'upload_group' => ['translation' => $rawUploadGroup['translation'], 'uploads' => $uploads]
        ];
    }

    private function webSiteUrl(array $upload): string
    {
        $hasHref = 0 < strlen($upload['href']);
        $href = $hasHref ? $upload['href'] : $upload['src'];
        $uploadHref = self::UPLOAD_BASE_URL . '1024/' . $href . '.jpg';
        return match ($upload['type']) {
            'external' => $this->externalWebSiteUrl($upload['website_url']),
            default => $uploadHref
        };
    }

    private function externalWebSiteUrl(string $webSiteUrl): string
    {
        $rawWebSiteUrl = $this->database->entity('website_url', $webSiteUrl);
        $rawWebSite = $this->database->entity('website', $rawWebSiteUrl['website']);
        return $rawWebSite['url'] . $rawWebSiteUrl['url'];
    }
}
