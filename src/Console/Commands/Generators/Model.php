<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands\Generators;

use Illuminate\Support\Str;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudGenerator;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudMakeCommand;

class Model extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command, string $base_path)
    {
        $name = Str::of($command->argument('name'))->singular()->studly();

        $translatable = $command->option('translatable');

        $hasMedia = $command->option('has-media');


        $dir = base_path($base_path . 'app/Models/Translations');

        // ensure directory exists
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }


        if ($translatable) {
            static::put(
                base_path($base_path."app/Models/Translations"),
                $name.'Translation.php',
                self::qualifyContent(
                    __DIR__.'/../stubs/Model/Translations/Translation.stub',
                    $name,
                    $module_name = Str::of($command->option('module'))->singular()->studly()
                )
            );
        }

        if ($translatable && $hasMedia) {
            static::put(
                base_path($base_path."app/Models"),
                $name.'.php',
                self::qualifyContent(
                    __DIR__.'/../stubs/Model/TranslatableMediaModel.stub',
                    $name,
                    $module_name = Str::of($command->option('module'))->singular()->studly()
                )
            );
        } elseif ($translatable && ! $hasMedia) {
            static::put(
                base_path($base_path."app/Models"),
                $name.'.php',
                self::qualifyContent(
                    __DIR__.'/../stubs/Model/TranslatableModel.stub',
                    $name,
                    $module_name = Str::of($command->option('module'))->singular()->studly()
                )
            );
        } elseif (! $translatable && $hasMedia) {
            static::put(
                base_path($base_path."app/Models"),
                $name.'.php',
                self::qualifyContent(
                    __DIR__.'/../stubs/Model/MediaModel.stub',
                    $name,
                    $module_name = Str::of($command->option('module'))->singular()->studly()
                )
            );
        } elseif (! $translatable && ! $hasMedia) {
            static::put(
                base_path($base_path."app/Models"),
                $name.'.php',
                self::qualifyContent(
                    __DIR__.'/../stubs/Model/Model.stub',
                    $name,
                    $module_name = Str::of($command->option('module'))->singular()->studly()
                )
            );
        }
    }
}
