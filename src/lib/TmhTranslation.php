<?php

namespace lib;

class TmhTranslation
{
//    private array $locales;
//
//    public function __construct(private TmhJson $json, private string $locale)
//    {
//        $this->load();
//    }
//
//    public function initialize(TmhJson $json, string $locale): void
//    {
//        $this->load($json, $locale);
//    }
//
//    public function get(string $key): string
//    {
//        return in_array($key, array_keys($this->locales)) ? $this->locales[$key] : $key;
//    }
//
//    public function getMany(array $keys): array
//    {
//        $many = [];
//        foreach ($keys as $key) {
//            $many[] = $this->get($key);
//        }
//        return $many;
//    }
//
//    private function load(TmhJson $json, string $locale): void
//    {
//        $this->locales = [];
//        $files = scandir(__DIR__ . '/../tmh_locales/src/' . $locale);
//        foreach ($files as $file) {
//            if ($file === '.' || $file === '..') {
//                continue;
//            }
//            $fileName = str_replace('.json', '', $file);
//            $locales = $json->load(__DIR__ . '/../tmh_locales/src/' . $locale . '/', $fileName);
//            foreach ($locales as $key => $value){
//                $this->locales[$key] = $value;
//            }
//        }
//    }
}