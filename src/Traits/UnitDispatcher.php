<?php

namespace Lucid\Traits;

use Illuminate\Bus\Dispatcher;

trait UnitDispatcher
{
    public function run($unit)
    {
        return app(Dispatcher::class)->dispatch($unit);
    }
}
