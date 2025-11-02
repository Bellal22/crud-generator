<?php

namespace Bellal22\CrudGenerator\Console\Commands;

use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class Modifier
{
    public static function routes($name,$base_path)
    {
        $pattern = '/\/\*\s*The routes of generated crud will set here: Don\'t remove this line\s*\*\//i';

        $dashboardPath = base_path($base_path . 'routes/dashboard.php');
        $apiPath       = base_path($base_path . 'routes/api.php');

        // Ensure routes directory exists
        $routesDir = base_path($base_path . 'routes');
        if (!is_dir($routesDir)) {
            mkdir($routesDir, 0755, true);
        }

        // Load or initialize route files
        $dashboard = file_exists($dashboardPath)
            ? file_get_contents($dashboardPath)
            : "<?php\n\n/*  The routes of generated crud will set here: Don't remove this line  */";

        $api = file_exists($apiPath)
            ? file_get_contents($apiPath)
            : "<?php\n\n/*  The routes of generated crud will set here: Don't remove this line  */";

        // Controller and resource names
        $controllerName = Str::of($name)->singular()->studly() . 'Controller';
        $resource       = Str::of($name)->plural()->snake();
        $singular       = $resource->singular();
        $studly         = $resource->plural()->studly();

        // Dashboard routes
        $dashboardRoute = <<<DASHBOARD
// $studly Routes.
Route::get('trashed/$resource', '$controllerName@trashed')->name('$resource.trashed');
Route::get('trashed/$resource/{trashed_$singular}', '$controllerName@showTrashed')->name('$resource.trashed.show');
Route::post('$resource/{trashed_$singular}/restore', '$controllerName@restore')->name('$resource.restore');
Route::delete('$resource/{trashed_$singular}/forceDelete', '$controllerName@forceDelete')->name('$resource.forceDelete');
Route::resource('$resource', '$controllerName');

/*  The routes of generated crud will set here: Don't remove this line  */
DASHBOARD;

        // API routes
        $apiRoutes = <<<API
// $studly Routes.
Route::apiResource('$resource', '$controllerName');
Route::get('/select/$resource', '$controllerName@select')->name('$resource.select');

/*  The routes of generated crud will set here: Don't remove this line  */
API;

        // Replace or append in dashboard.php
        if (preg_match($pattern, $dashboard)) {
            $dashboard = preg_replace($pattern, $dashboardRoute, $dashboard);
        } else {
            $dashboard .= "\n\n" . $dashboardRoute;
        }

        // Replace or append in api.php
        if (preg_match($pattern, $api)) {
            $api = preg_replace($pattern, $apiRoutes, $api);
        } else {
            $api .= "\n\n" . $apiRoutes;
        }

        // Save back to files
        file_put_contents($dashboardPath, $dashboard);
        file_put_contents($apiPath, $api);
    }

    public function sidebar($name,$base_path)
    {
        // TODO: CRUD 2
        $pattern = '/\{\{\-\-\s*The sidebar of generated crud will set here: Don\'t remove this line\s*\-\-\}\}/i';

        $sidebarPath = base_path($base_path . 'views/layouts/sidebar.blade.php');
        $layoutsDir  = dirname($sidebarPath);

        // Ensure layouts directory exists
        if (!is_dir($layoutsDir)) {
            mkdir($layoutsDir, 0755, true);
        }

        // Load or initialize sidebar file
        $sidebarFile = file_exists($sidebarPath)
            ? file_get_contents($sidebarPath)
            : "@extends('layouts.master')\n\n{{-- The sidebar of generated crud will set here: Don't remove this line --}}";

        $resource = Str::of($name)->plural()->snake();

        $sidebar = <<<SIDEBAR
@include('dashboard.$resource.partials.actions.sidebar')
{{-- The sidebar of generated crud will set here: Don't remove this line --}}
SIDEBAR;

        // Replace or append depending on pattern existence
        if (preg_match($pattern, $sidebarFile)) {
            $sidebarFile = preg_replace($pattern, $sidebar, $sidebarFile);
        } else {
            $sidebarFile .= "\n\n" . $sidebar;
        }

        // Save updated content
        file_put_contents($sidebarPath, $sidebarFile);
    }

    public function permission($name, $base_path)
    {
        $resource = Str::of($name)->plural()->snake();

        // Reset cached roles and permissions
//        app()[PermissionRegistrar::class]->forgetCachedPermissions();
//
//        // Create or update permission
//        Permission::updateOrCreate(['name' => "manage.$resource"]);

        $file = base_path($base_path . 'storage/permissions.json');
        $dir = dirname($file);
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $permissions = @json_decode(file_get_contents($file), true) ?? [];

        // Avoid duplicates
        if (!in_array("manage.$resource", $permissions)) {
            $permissions[] = "manage.$resource";
        }

        file_put_contents($file, json_encode($permissions, JSON_PRETTY_PRINT));
    }

    public function softDeletes($name, $base_path)
    {
        $resource = Str::of($name)->singular()->snake();

        $file = base_path($base_path . 'storage/soft_deletes_route_binding.json');
        $dir = dirname($file);
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $map = @json_decode(file_get_contents($file), true) ?? [];

        $key = "trashed_$resource";
        $value = "App\\Models\\" . Str::of($name)->singular()->studly();

        // Update or add key
        $map[$key] = $value;

        file_put_contents($file, json_encode($map, JSON_PRETTY_PRINT));
    }

    public function seeder($name, $base_path)
    {
        $resource = Str::of($name)->singular()->studly();

        $path = base_path($base_path . 'database/seeders/DummyDataSeeder.php');
        $dir = dirname($path);
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        // Create the file if not exists
        if (!file_exists($path)) {
            file_put_contents($path, "<?php\n\nclass DummyDataSeeder extends Seeder\n{\n    public function run()\n    {\n        /*  The seeders of generated crud will set here: Don't remove this line  */\n    }\n}");
        }

        $content = file_get_contents($path);
        $pattern = '/\/\*\s*The seeders of generated crud will set here: Don\'t remove this line\s*\*\//';

        $newLine = "\$this->call({$resource}Seeder::class);\n        /*  The seeders of generated crud will set here: Don't remove this line  */";

        // Avoid duplicate seeder calls
        if (!str_contains($content, "\$this->call({$resource}Seeder::class);")) {
            $content = preg_replace($pattern, $newLine, $content);
            file_put_contents($path, $content);
        }
    }

    public function langGenerator($name, $base_path)
    {
        $resource = Str::of($name)->plural()->snake();

        $path = base_path($base_path . 'config/lang-generator.php');
        $dir = dirname($path);
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        // Create the file if not exists
        if (!file_exists($path)) {
            file_put_contents($path, "<?php\n\nreturn [\n    /*  The lang of generated crud will set here: Don't remove this line  */\n];");
        }

        $content = file_get_contents($path);
        $pattern = '/\/\*\s*The lang of generated crud will set here: Don\'t remove this line\s*\*\//';

        $newLang = "'$resource' => base_path('lang/{lang}/$resource.php'),\n    /*  The lang of generated crud will set here: Don't remove this line  */";

        // Avoid duplicate lines
        if (!str_contains($content, "'$resource' => base_path('lang/{lang}/$resource.php')")) {
            $content = preg_replace($pattern, $newLang, $content);
            file_put_contents($path, $content);
        }
    }
}
