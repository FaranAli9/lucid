<?php

namespace Lucid\Generators;

use Exception;
use Illuminate\Support\Str;
use Lucid\Exceptions\ServiceAlreadyExistsException;
use Lucid\Traits\Finder;

class ServiceGenerator extends Generator
{
    use Finder;

    private array  $directories = [
        'Http/',
        'Http/Controllers/',
        'Http/Middleware/',
        'Providers/',
        'Features/',
        'Operations/',
        'routes',
    ];
    private string $name;

    private string $servicePath;

    private string $slug;

    private string $namespace;

    /**
     * @throws Exception
     */
    public function __construct(string $name)
    {
        $this->name        = Str::studly($name);
        $this->servicePath = app_path('Services' . DIRECTORY_SEPARATOR . $name);
        $this->slug        = Str::kebab($this->name);
        $this->namespace   = $this->findNamespace();
    }

    /**
     * @return array
     * @throws ServiceAlreadyExistsException
     */
    public function generate(): array
    {
        $this->createRootDirectory();
        $this->createDirectories();
        $this->createServiceProviders();
        $this->createRoutesFiles();

        return [
            'name'     => $this->name,
            'provider' => implode('', [$this->namespace, '\\Services\\', $this->name, '\\Providers\\', $this->name, 'ServiceProvider'])
        ];

    }

    /**
     * @throws ServiceAlreadyExistsException
     */
    public function createDirectories(): void
    {
        $root = $this->servicePath;
        foreach ($this->directories as $directory) {
            $path = $root . DIRECTORY_SEPARATOR . $directory;
            $this->createDirectory($path);
            $this->createFile($path . DIRECTORY_SEPARATOR . '.gitkeep', '');
        }
    }

    /**
     * @throws ServiceAlreadyExistsException
     */
    public function createRootDirectory(): void
    {
        $this->createDirectory($this->servicePath);
    }

    public function createServiceProviders(): void
    {
        $root = $this->servicePath . DIRECTORY_SEPARATOR . 'Providers' . DIRECTORY_SEPARATOR;
        $this->createRegistrationServiceProvider($root);
        $this->createRouteServiceProvider($root);
    }

    private function createRegistrationServiceProvider($root): void
    {
        $path     = $root . $this->name . 'ServiceProvider.php';
        $contents = file_get_contents(__DIR__ . '/stubs/service.provider.stub');

        $contents = Str::replace(['{{namespace}}', '{{name}}'], [$this->namespace, $this->name], $contents);
        $this->createFile($path, $contents);
    }

    private function createRouteServiceProvider($root): void
    {
        $path     = $root . 'RouteServiceProvider.php';
        $contents = file_get_contents(__DIR__ . '/stubs/route.service.provider.stub');
        $contents = Str::replace(
            ['{{namespace}}', '{{name}}', '{{slug}}'],
            [$this->namespace, $this->name, $this->slug],
            $contents
        );
        $this->createFile($path, $contents);
    }

    private function createRoutesFiles(): void
    {
        $path     = $this->servicePath . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'api.php';
        $contents = file_get_contents(__DIR__ . '/stubs/routes.api.stub');
        $contents = Str::replace(
            ['{{name}}', '{{slug}}'],
            [$this->name, $this->slug],
            $contents
        );
        $this->createFile($path, $contents);
    }
}
