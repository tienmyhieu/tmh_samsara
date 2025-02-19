<?php

namespace lib;

class TmhJson
{
    public function database(): array
    {
        $database = [];
        foreach (scandir(__DIR__ . '/../database') as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $fileName = str_replace('.json', '', $file);
            $database[$fileName] = $this->loadFile(__DIR__ . '/../database/', $fileName);
        }
        return $database;
    }

    public function structures(): array
    {
        $structure = [];
        foreach (scandir(__DIR__ . '/../structures') as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $fileName = str_replace('.json', '', $file);
            $structure[$fileName] = $this->loadFile(__DIR__ . '/../structures/', $fileName);
        }
        return $structure;
    }

    public function translations(): array
    {
        $locales = [];
        $localesDirectory = __DIR__ . '/../locales';
        foreach (scandir($localesDirectory) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            if (is_dir($localesDirectory . '/' . $file)) {
                $locales[$file] = $this->loadDirectoryFiles($localesDirectory . '/' . $file);
            }
        }
        return $locales;
    }

    public function routes(): array
    {
        $routes = [];
        $allRoutes = $this->loadDirectoryFiles(__DIR__ . '/../routes');
        foreach ($allRoutes as $primaryKey => $route) {
            $routes[$primaryKey] = $route;
        }
        return $routes;
    }

    public function loadDirectoryFiles(string $directory): array
    {
        $unified = [];
        foreach (scandir($directory) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $contents = $this->loadFile($directory . '/', str_replace('.json', '', $file));
            foreach ($contents as $key => $value){
                $unified[$key] = $value;
            }
        }
        return $unified;
    }

    public function loadFile(string $path, string $file, bool $associative = true): array
    {
        $contents = '[]';
        if ($this->exists($path . $file . '.json')) {
//            echo "<pre>" . 'reading ' . $path . $file . PHP_EOL . "</pre>";
            $contents = file_get_contents($path . $file . '.json');
        } else {
//            echo "<pre>" . 'not reading ' . $path . $file . PHP_EOL . "</pre>";
        }
        return json_decode($contents, $associative);
    }

    private function exists(string $url): bool
    {
        return (false !== @file_get_contents($url, 0, null, 0, 1));
    }
}
