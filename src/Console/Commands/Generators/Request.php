<?php

namespace Bellal22\CrudGenerator\Console\Commands\Generators;

use Illuminate\Support\Str;
use Bellal22\CrudGenerator\Console\Commands\CrudGenerator;
use Bellal22\CrudGenerator\Console\Commands\CrudMakeCommand;

class Request extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command, string $base_path)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();

        $translatable = $command->option('translatable');

        $namespace = Str::of($name)->plural()->studly();

        $stub = $translatable
            ? __DIR__.'/../stubs/Requests/TranslatableRequest.stub'
            : __DIR__.'/../stubs/Requests/Request.stub';


        $dir = base_path($base_path . 'app/Http/Requests/Dashboard');

        // ensure directory exists
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }


        static::put(
            base_path($base_path."app/Http/Requests/Dashboard"),
            $name.'Request.php',
            self::qualifyContent(
                $stub,
                $name,
                $module_name = Str::of($command->option('module'))->singular()->studly()
            )
        );
    }
}
