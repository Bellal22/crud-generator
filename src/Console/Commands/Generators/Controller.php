<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands\Generators;

use Illuminate\Support\Str;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudGenerator;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudMakeCommand;

class Controller extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command, string $base_path)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();
        $module_name = Str::of($command->option('module'))->singular()->studly();

        $namespace = Str::of($name)->plural()->studly();

        $hasMedia = $command->option('has-media');

        $apiDir = base_path($base_path . "app/Http/Controllers/Api");
        $dashboardDir = base_path($base_path . "app/Http/Controllers/Dashboard");

// ensure directories exist
        if (!is_dir($apiDir)) {
            mkdir($apiDir, 0755, true);
        }

        if (!is_dir($dashboardDir)) {
            mkdir($dashboardDir, 0755, true);
        }

// ApiController
        static::put(
            $apiDir,
            $name . 'Controller.php',
            self::qualifyContent(
                __DIR__ . '/../stubs/Controllers/Api/Controller.stub',
                $name,
                $module_name
            )
        );

// choose stub
        $stub = $hasMedia
            ? __DIR__ . '/../stubs/Controllers/Dashboard/MediaController.stub'
            : __DIR__ . '/../stubs/Controllers/Dashboard/Controller.stub';

// DashboardController
        static::put(
            $dashboardDir,
            $name . 'Controller.php',
            self::qualifyContent(
                $stub,
                $name,
                $module_name
            )
        );

    }
}
