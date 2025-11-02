<?php

namespace Bellal22\CrudGenerator\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Bellal22\CrudGenerator\Console\Commands\Generators\Lang;
use Bellal22\CrudGenerator\Console\Commands\Generators\Test;
use Bellal22\CrudGenerator\Console\Commands\Generators\View;
use Bellal22\CrudGenerator\Console\Commands\Generators\Model;
use Bellal22\CrudGenerator\Console\Commands\Generators\Filter;
use Bellal22\CrudGenerator\Console\Commands\Generators\Policy;
use Bellal22\CrudGenerator\Console\Commands\Generators\Seeder;
use Bellal22\CrudGenerator\Console\Commands\Generators\Factory;
use Bellal22\CrudGenerator\Console\Commands\Generators\Request;
use Bellal22\CrudGenerator\Console\Commands\Generators\Resource;
use Bellal22\CrudGenerator\Console\Commands\Generators\Migration;
use Bellal22\CrudGenerator\Console\Commands\Generators\Breadcrumb;
use Bellal22\CrudGenerator\Console\Commands\Generators\Controller;

class CrudMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module-crud
                            {name : Class (Singular), e.g User, Place, Car}
                            {--translatable : Whether the model supports translations}
                            {--has-media : Whether the model has media}
                            {--module= : Optional module name (e.g. Source)}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CRUD files for a module';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $module =  $this->option('module');
        $base_path = '';
        if ($module)
            $base_path = "Modules/${module}/";
        Lang::generate($this,$base_path);
        Breadcrumb::generate($this,$base_path);
        View::generate($this,$base_path);
        Resource::generate($this,$base_path);
        Controller::generate($this,$base_path);
        Model::generate($this,$base_path);
        Request::generate($this,$base_path);
        Filter::generate($this,$base_path);
        Migration::generate($this,$base_path);
        Policy::generate($this,$base_path);
        Factory::generate($this,$base_path);
        Seeder::generate($this,$base_path);
        Test::generate($this,$base_path);

        $name = $this->argument('name');

        app(Modifier::class)->routes($name,$base_path);

        app(Modifier::class)->sidebar($name,$base_path);
//
        app(Modifier::class)->seeder($name,$base_path);

        app(Modifier::class)->permission($name,$base_path);

        app(Modifier::class)->softDeletes($name,$base_path);

        app(Modifier::class)->langGenerator($name,$base_path);

        $seederName = Str::of($name)->singular()->studly().'Seeder';

        $this->info('Api Crud for '.$name.' created successfully 🎉');
        $this->warn('Please run "composer dump-autoload && php artisan migrate && php artisan db:seed --class='.$seederName.'"');
    }
}
