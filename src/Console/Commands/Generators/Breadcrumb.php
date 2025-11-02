<?php

namespace Bellal22\CrudGenerator\Console\Commands\Generators;

use Illuminate\Support\Str;
use Bellal22\CrudGenerator\Console\Commands\CrudGenerator;
use Bellal22\CrudGenerator\Console\Commands\CrudMakeCommand;

class Breadcrumb extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command, string $base_path)
    {
        $name = Str::of($command->argument('name'))->plural()->snake();

        $stub = __DIR__.'/../stubs/breadcrumbs.stub';

        $dir = base_path($base_path . 'routes/breadcrumbs');

        // ensure directory exists
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }


        static::put(
            base_path($base_path."routes/breadcrumbs"),
            $name.'.php',
            self::qualifyContent(
                $stub,
                $name,
                $module_name = Str::of($command->option('module'))->singular()->studly()
            )
        );
    }
}
