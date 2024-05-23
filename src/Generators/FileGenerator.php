<?php

namespace Lucid\Generators;

use Exception;
use Illuminate\Support\Str;
use Lucid\Exceptions\ServiceDoesNotExistException;
use Lucid\Traits\Finder;

abstract class FileGenerator extends Generator
{
    use Finder;

    private string $type;

    private string $name;

    private array $elements;

    private string $unit;

    protected const CONTROLLER = 'controller';

    protected const FEATURE = 'feature';

    protected const JOB = 'job';

    public function __construct(string $type, string $name, string $unit)
    {
        $this->type = $type;

        if (! Str::endsWith($name, ucfirst($type))) {
            $name .= ucfirst($type);
        }

        $elements       = explode('/', $name);
        $this->name     = array_pop($elements);
        $this->elements = $elements;
        $this->unit     = $unit;
    }

    /**
     * @throws Exception
     */
    public function generate(): array
    {
        if ($this->isServiceUnit() && ! $this->serviceExists($this->unit)) {
            throw new ServiceDoesNotExistException("$this->unit service does not exist.");
        }

        $path = $this->createFilePath();

        if (file_exists($path)) {
            throw new Exception('File already exists.');
        }

        $contents = $this->generateContentFromStub();

        $this->createFile($path, $contents);

        return ['path' => $this->relativeFromReal($path)];
    }

    abstract protected function getStubFilePath(): string;

    /**
     * @throws Exception
     */
    protected function generateContentFromStub(): string
    {
        $contents = file_get_contents($this->getStubFilePath());

        return Str::replace(
            ['{{namespace}}', '{{name}}'],
            [$this->getNamespace(), $this->name],
            $contents
        );
    }

    abstract protected function getUnitPathElements(): array;

    protected function isServiceUnit(): bool
    {
        return in_array($this->type, [self::CONTROLLER, self::FEATURE]);
    }

    private function createFilePath(): string
    {
        if ($this->isServiceUnit()) {
            $base = $this->findServicesRootPath();
        } else {
            $base = $this->findSourceRoot().DIRECTORY_SEPARATOR.'Domains';
        }

        $path = $this->createRecursiveDirectories($base, [
            $this->unit,
            ...$this->getUnitPathElements(),
            ...$this->elements,
        ]);

        return $path.DIRECTORY_SEPARATOR.$this->name.'.php';
    }

    /**
     * @throws Exception
     */
    private function getNamespace(): string
    {
        if ($this->isServiceUnit()) {
            $base = $this->findServiceNamespace($this->unit);
        } else {
            $base = $this->findRootNamespace().'\\'.'Domains'.'\\'.$this->unit;
        }

        return implode('\\', [
            $base,
            ...$this->getUnitPathElements(),
            ...$this->elements,
        ]);
    }
}
