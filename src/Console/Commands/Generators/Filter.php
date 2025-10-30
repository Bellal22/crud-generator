<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands\Generators;

use Illuminate\Support\Str;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudGenerator;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudMakeCommand;

class Filter extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command, string $base_path)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();

        $namespace = Str::of($name)->plural()->studly();
        $module_name = Str::of($command->option('module'))->singular()->studly();


        $translatable = $command->option('translatable');

        $filterStub = $translatable
            ? __DIR__.'/../stubs/Filters/TranslatableFilter.stub'
            : __DIR__.'/../stubs/Filters/Filter.stub';

        $dir = base_path($base_path . 'app/Http/Filters');

        // ensure directory exists
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        static::put(
            base_path($base_path."app/Http/Filters"),
            $name.'Filter.php',
            self::qualifyContent(
                $filterStub,
                $name,
                $module_name
            )
        );
    }
}
