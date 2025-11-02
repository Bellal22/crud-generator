<?php

namespace Bellal22\CrudGenerator\Console\Commands\Generators;

use Illuminate\Support\Str;
use Bellal22\CrudGenerator\Console\Commands\CrudGenerator;
use Bellal22\CrudGenerator\Console\Commands\CrudMakeCommand;

class Seeder extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command, string $base_path)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();

        $stub = __DIR__.'/../stubs/Seeder.stub';

        static::put(
            base_path($base_path."database/seeders"),
            $name.'Seeder.php',
            self::qualifyContent(
                $stub,
                $name,
                $module_name = Str::of($command->option('module'))->singular()->studly()
            )
        );
    }
}
