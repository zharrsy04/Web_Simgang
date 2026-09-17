<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeCssFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:css {name=style}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat File Css baru';

    /**
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = public_path("css/{$name}.css");

        if(File::exists($path)) {
            $this->error("File {$name}.css file sudah ada!");
            return Command::FAILURE;
        }
        File::ensureDirectoryExists(public_path('css'));
        File::put($path,"/* {$name}.css */");

        $this->info("File {$name}.css berhasil dibuat!");
        return Command::SUCCESS;
    }
}
