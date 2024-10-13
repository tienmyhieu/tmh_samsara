<?php

namespace lib\Core;

class TmhJson
{
    public function attributes(): array
    {
        return $this->loadDirectoryFilesByFileName(__DIR__ . '/../../attributes');
    }

    public function database(): array
    {
        return $this->loadDirectoryFilesByFileName(__DIR__ . '/../../database');
    }

    public function translations(): array
    {
        $locales = [];
        $localesDirectory = __DIR__ . '/../../locales';
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
        $allRoutes = $this->loadDirectoryFiles(__DIR__ . '/../../routes');
        foreach ($allRoutes as $primaryKey => $route) {
            $routes[$primaryKey] = $route;
        }
        return $routes;
    }

    private function loadDirectoryFiles(string $directory): array
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

    private function loadDirectoryFilesByFileName(string $directory): array
    {
        $directoryFiles = [];
        foreach (scandir($directory) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $fileName = str_replace('.json', '', $file);
            $directoryFiles[$fileName] = $this->loadFile($directory . '/', $fileName);
        }
        return $directoryFiles;
    }

    private function loadFile(string $path, string $file, bool $associative = true): array
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
