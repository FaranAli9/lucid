<?php

namespace Lucid\Generators;

use Lucid\Traits\Finder;

class FeatureGenerator extends FileGenerator
{
    use Finder;

    public function __construct(string $name, string $service)
    {
        parent::__construct(self::FEATURE, $name, $service);

    }

    protected function getUnitPathElements(): array
    {
        return [
            'Features',
        ];
    }

    protected function getStubFilePath(): string
    {
        return __DIR__.'/stubs/feature.stub';
    }
}
