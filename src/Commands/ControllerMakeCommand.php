<?php

namespace Lucid\Commands;

use Exception;
use Lucid\Generators\ControllerGenerator;
use Lucid\Traits\Finder;
use Symfony\Component\Console\Input\InputArgument;

class ControllerMakeCommand extends LucidCommand
{
    use Finder;

    /**
     * The console command name.
     */
    protected string $name = 'make:controller';

    /**
     * The console command description.
     */
    protected string $description = 'Create a new Controller';

    /**
     * @throws Exception
     */
    public function handle(): int
    {
        $name      = $this->argument('name');
        $service   = $this->argument('service');
        $generator = new ControllerGenerator($name, $service);
        try {
            $meta = $generator->generate();
            $this->info('Controller class created successfully.'.
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
