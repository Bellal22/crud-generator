<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands\Generators;

use Illuminate\Support\Str;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudGenerator;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudMakeCommand;

class Resource extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command, string $base_path)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();

        $namespace = Str::of($name)->plural()->studly();

        $hasMedia = $command->option('has-media');

        $stub = __DIR__.'/../stubs/Resources/Resource.stub';

        $dir = base_path($base_path . 'app/Http/Resources');

        // ensure directory exists
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        static::put(
            base_path($base_path."app/Http/Resources"),
            $name.'Resource.php',
            self::qualifyContent(
                $stub,
                $name,
                $module_name = Str::of($command->option('module'))->singular()->studly()
            )
        );
    }
}
