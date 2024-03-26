<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Vite::useScriptTagAttributes(fn (string $src, string $url, ?array $chunk, ?array $manifest) => [
            'async' => $src === 'resources/ts/set-theme.ts',
        ]);
    }
}
