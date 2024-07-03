<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Provides an interface for interacting with Laravel's caching system, encapsulating the logic for setting, getting, and clearing cache values.
     */
    public function remember($key, $duration, callable $callback)
    {
        return Cache::remember($key, $duration, $callback);
    }
}