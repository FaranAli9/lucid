<?php

namespace Lucid\Commands;

use Exception;
use Lucid\Generators\ServiceGenerator;
use Lucid\Traits\Finder;
use Symfony\Component\Console\Input\InputArgument;

class ServiceMakeCommand extends LucidCommand
{
    use Finder;

    /**
     * The console command name.
     */
    protected string $name = 'make:service';

    /**
     * The console command description.
     */
    protected string $description = 'Create a new Service';

    /**
     * @throws Exception
     */
    public function handle(): int
    {
        $generator = new ServiceGenerator($this->argument('name'));

        try {
            $meta = $generator->generate();
            $this->info($meta['name'].' Service created successfully.');
            $this->info('Activate it by adding '.
                '<comment>'.$meta['provider']."::class</comment>\n".
                'in <comment>bootstrap/providers.php</comment>'
            );
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }

    public function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The service name.'],
        ];
    }
}
