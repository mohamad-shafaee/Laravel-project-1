<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Pluralizer;
use Illuminate\Filesystem\Filesystem;

class MakeModelInCustomPath extends Command
{




    // I did not complete this file, since it takes much time to 
    // understand and write a good model maker. 
    // Now I will follow the project by manually adding the models in 
    // the wanted directories. 

    // I should follow the class ModelMakeCommand in the
    // namespace Illuminate\Foundation\Console


     // I followed this site:
     //https://medium.com/@ariadoos/laravel-custom-file-stubs-ed32f046ea81



    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:model-p {name} {package}';
    //protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent model class in a Summit package.';

    protected $type = 'Model';

    protected $files;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function getSingularClassName($name){

        return ucwords(Pluralizer::singular($name));
    }

    public function getStubPath(){
        return __DIR__ . '/../../../stubs/model.stub';

    }

    public function getStubVariables(){

        return [
            'namespace'=>'Summit\\' . $this->argument('package') . '\\Models',
            'class'    =>$this->getSingularClassName($this->argument('name'))
        ];
    }

    public function getStubContents($stub, $stubVariables=[]){

        $contents = file_get_contents($stub);
        foreach($stubVariables as $search => $replace){
           $contents = str_replace('{{ ' .  $search . ' }}', $replace, $contents);

        }
        return $contents;
    }

    public function getSourceFile(){

        return $this->getStubContents($this->getStubPath(), $this->getStubVariables());
    }

    public function getSourceFilePath(){


        return base_path('packages/summit') . '/' . $this->argument('package') .
        '/' . 'src/Models' . '/' .
         $this->getSingularClassName($this->argument('name')) . '.php';
    }

    protected function makeDirectory($path){

        if(! $this->files->isDirectory($path)){
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = $this->getSourceFilePath();
        $this->makeDirectory(dirname($path));

        $contents = $this->getSourceFile();

        if(! $this->files->exists($path)){
            $this->files->put($path, $contents);
            $this->info("File: {$path} created.");
        }else{
            $this->info("File: {$path} already exists.");
        }

    }
}
