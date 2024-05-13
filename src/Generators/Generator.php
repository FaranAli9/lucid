<?php

namespace Lucid\Generators;

use Lucid\Exceptions\ServiceAlreadyExistsException;

abstract class Generator
{


    protected function createDirectory($path): void
    {
       if(!is_dir($path)) {
           mkdir($path);
       }
    }

    protected function createRecursiveDirectories(string $root, array $elements): string
    {
        $path = $root;
        foreach ($elements as $element) {
            $path = $path . DIRECTORY_SEPARATOR . $element;
            $this->createDirectory($path);
        }
        return $path;
    }


    protected function createFile(string $path, string $contents): void
    {
        file_put_contents($path, $contents);
    }
}
