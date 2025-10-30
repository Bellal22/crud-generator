<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands\Generators;

use Illuminate\Support\Str;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudGenerator;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudMakeCommand;

class Policy extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command, string $base_path)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();

        $stub = __DIR__.'/../stubs/Policy/Policy.stub';

        static::put(
            base_path($base_path."app/Policies"),
            $name.'Policy.php',
            self::qualifyContent(
                $stub,
                $name,
                $module_name = Str::of($command->option('module'))->singular()->studly()
            )
        );
    }
}
