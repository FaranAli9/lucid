<?php

namespace Lucid\Units;

use Illuminate\Bus\Dispatcher;

abstract class Feature
{

    public function run($unit)
    {
        return app(Dispatcher::class)->dispatch($unit);
    }
}
