<?php

namespace Lucid\Generators;

use Lucid\Traits\Finder;

class ControllerGenerator extends FileGenerator
{
    use Finder;

    public function __construct(string $name, string $service)
    {
        parent::__construct(self::CONTROLLER, $name, $service);

    }

    protected function getUnitPathElements(): array
    {
        return [
            'Http',
            'Controllers',
        ];
    }

    protected function getStubFilePath(): string
    {
        return __DIR__.'/stubs/controller.stub';
    }
}
