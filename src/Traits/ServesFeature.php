<?php

namespace Lucid\Traits;

trait ServesFeature
{
    public function serve($feature, $params = [])
    {
        return dispatch_sync(new $feature(...$params));
    }
}
