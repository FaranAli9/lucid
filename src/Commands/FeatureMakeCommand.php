<?php

namespace Lucid\Commands;

use Exception;
use Lucid\Generators\FeatureGenerator;
use Lucid\Traits\Finder;
use Symfony\Component\Console\Input\InputArgument;

class FeatureMakeCommand extends LucidCommand
{
    use Finder;

    /**
     * The console command name.
     *
     * @var string
     */
    protected string $name = 'make:feature';

    /**
     * The console command description.
     *
     * @var string
     */
    protected string $description = 'Create a new Feature';


    /**
     * @throws Exception
     */
    public function handle(): int
    {
        $name      = $this->argument('name');
        $service   = $this->argument('service');
        $generator = new FeatureGenerator($name, $service);
        try {
            $meta = $generator->generate();
            $this->info('Feature class created successfully.'.
                "\n".
                "\n".
                'Find it at <comment>'.$meta['path'].'</comment>'."\n"
            );

        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }

        return 0;
    }

    public function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The controller name.'],
            ['service', InputArgument::REQUIRED, 'The service name.'],
        ];
    }
}
