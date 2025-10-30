<?php

namespace AhmedAliraqi\CrudGenerator\Console\Commands\Generators;

use Illuminate\Support\Str;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudGenerator;
use AhmedAliraqi\CrudGenerator\Console\Commands\CrudMakeCommand;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\File;


class View extends CrudGenerator
{
    public static function generate(CrudMakeCommand $command, string $base_path)
    {
        $name = Str::of($command->argument('name'))->plural()->snake();

        $translatable = $command->option('translatable');

        $hasMedia = $command->option('has-media');

        if ($translatable && $hasMedia) {
            $stubPath = __DIR__.'/../stubs/Views/translatable_has_media';
        } elseif ($translatable && ! $hasMedia) {
            $stubPath = __DIR__.'/../stubs/Views/translatable';
        } elseif (! $translatable && $hasMedia) {
            $stubPath = __DIR__.'/../stubs/Views/has_media';
        } else {
            $stubPath = __DIR__.'/../stubs/Views/default';
        }

        $baseDir = base_path($base_path . "resources/views/dashboard/{$name}");

        $path = "{$baseDir}/partials" ;

        if (!is_dir($path)) {
            mkdir($path, '0777', true);
        }

        $path = "{$baseDir}/partials/actions" ;

        if (!is_dir($path)) {
            mkdir($path, '0777', true);
        }

// Actions
        $actions = [
            'create', 'delete', 'edit', 'forceDelete',
            'link', 'restore', 'trashed', 'show', 'sidebar'
        ];

        foreach ($actions as $file) {
            static::put(
                "{$baseDir}/partials/actions",
                "{$file}.blade.php",
                self::qualifyContent(
                    "{$stubPath}/partials/actions/{$file}.blade.stub",
                    $name,
                    $module_name = Str::of($command->option('module'))->singular()->studly()
                )
            );
        }

// Partials
        $partials = ['filter', 'form'];

        foreach ($partials as $file) {
            static::put(
                "{$baseDir}/partials",
                "{$file}.blade.php",
                self::qualifyContent(
                    "{$stubPath}/partials/{$file}.blade.stub",
                    $name,
                    $module_name = Str::of($command->option('module'))->singular()->studly()
                )
            );
        }

// Resource
        $resources = ['create', 'edit', 'index', 'show', 'trashed'];

        foreach ($resources as $file) {
            static::put(
                $baseDir,
                "{$file}.blade.php",
                self::qualifyContent(
                    "{$stubPath}/{$file}.blade.stub",
                    $name,
                    $module_name = Str::of($command->option('module'))->singular()->studly()
                )
            );
        }
    }
}
