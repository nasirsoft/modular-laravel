<?php

namespace AkrilliA\LaravelBeyond;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class LaravelBeyondServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(...$this->beyondCommands());
        }
    }

    /**
     * @return array<string>
     */
    public function beyondCommands(): array
    {
        $exclude = [];

        $fs = new Filesystem();
        $files = $fs->files(beyond_os_aware_path(__DIR__.'/Commands'));

        return array_map(
            fn ($file) => 'AkrilliA\\LaravelBeyond\\Commands\\'.$file->getBasename('.php'),
            array_filter(
                $files,
                fn ($file) => ! in_array($file->getBasename('.php'), $exclude, true) // @phpstan-ignore-line
            )
        );
    }
}
