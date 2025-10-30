<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands\Generators;

use Illuminate\Support\Str;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudGenerator;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudMakeCommand;

class Test extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command, string $base_path)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();

        $dir = $name->plural();

        $translatable = $command->option('translatable');

        $path = $translatable
            ? __DIR__.'/../stubs/Tests/Dashboard/TranslatableTest.stub'
            : __DIR__.'/../stubs/Tests/Dashboard/Test.stub';

        static::put(
            base_path("tests/Feature/Api"),
            $name.'Test.php',
            self::qualifyContent(
                __DIR__.'/../stubs/Tests/Api/Test.stub',
                $name,
                $module_name = Str::of($command->option('module'))->singular()->studly()
            )
        );

        static::put(
            base_path("tests/Feature/Dashboard"),
            $name.'Test.php',
            self::qualifyContent($path, $name,
                $module_name = Str::of($command->option('module'))->singular()->studly())
        );
    }
}
