<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    public function remember($key, $duration, callable $callback)
    {
        return Cache::remember($key, $duration, $callback);
    }
}