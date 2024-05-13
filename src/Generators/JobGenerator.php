<?php

namespace Lucid\Generators;

use Lucid\Traits\Finder;

class JobGenerator extends FileGenerator
{
    use Finder;

    public function __construct(string $name, string $service)
    {
        parent::__construct(self::JOB, $name, $service);

    }
    protected function getUnitPathElements(): array
    {
        return [
            'Jobs',
        ];
    }

    protected function getStubFilePath(): string
    {
        return __DIR__ . '/stubs/job.stub';
    }
}
