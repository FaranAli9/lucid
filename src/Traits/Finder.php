<?php

namespace Lucid\Traits;

use Exception;
use Illuminate\Support\Facades\File;

trait Finder
{
    /**
     * get the root of the source directory.
     */
    public function findSourceRoot(): string
    {
        return app_path();
    }

    public function serviceExists(string $name): bool
    {

        return file_exists($this->findServicesRootPath().DIRECTORY_SEPARATOR.$name);
    }

    /**
     * Find the root path of all the services.
     */
    public function findServicesRootPath(): string
    {
        return $this->findSourceRoot().DIRECTORY_SEPARATOR.'Services';
    }

    /**
     * @throws Exception
     */
    public function findNamespace(?string $dir = null): string
    {
        $dir = $dir ?? $this->getSourceDirectoryName();

        // read composer.json file contents to determine the namespace
        $composer = json_decode(file_get_contents(base_path().DIRECTORY_SEPARATOR.'composer.json'), true);

        // see which one refers to the "src/" directory
        foreach ($composer['autoload']['psr-4'] as $namespace => $directory) {
            $directory = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $directory);
            if ($directory === $dir.DIRECTORY_SEPARATOR) {
                return trim($namespace, '\\');
            }
        }

        throw new Exception('App namespace not set in composer.json');
    }

    /**
     * @throws Exception
     */
    public function findServiceNamespace(string $service): string
    {
        $root = $this->findRootNamespace();

        return "$root\\Services\\$service";
    }

    /**
     * @throws Exception
     */
    public function findRootNamespace(): string
    {
        return $this->findNamespace($this->getSourceDirectoryName());
    }

    public function getSourceDirectoryName(): string
    {
        return 'app';
    }

    /**
     * Get the relative version of the given real path.
     *
     * @param  string  $path
     * @param  string  $needle
     */
    protected function relativeFromReal($path, $needle = ''): string
    {
        if (! $needle) {
            $needle = $this->getSourceDirectoryName().DIRECTORY_SEPARATOR;
        }

        return strstr($path, $needle);
    }
}
