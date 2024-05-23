<?php

namespace Lucid\Commands;

use Exception;
use Lucid\Generators\JobGenerator;
use Lucid\Traits\Finder;
use Symfony\Component\Console\Input\InputArgument;

class JobMakeCommand extends LucidCommand
{
    use Finder;

    /**
     * The console command name.
     */
    protected string $name = 'make:job';

    /**
     * The console command description.
     */
    protected string $description = 'Create a new Job';

    /**
     * @throws Exception
     */
    public function handle(): int
    {
        $name      = $this->argument('name');
        $service   = $this->argument('domain');
        $generator = new JobGenerator($name, $service);
        try {
            $meta = $generator->generate();
            $this->info('Job class created successfully.'.
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
            ['domain', InputArgument::REQUIRED, 'The domain name.'],
        ];
    }
}
