<?php

namespace App\Console\Commands;

use App\Migrate;
use App\Sale;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class ListMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sm:list-migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List migrations';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $migrates = Migrate::all();

        foreach ($migrates as $migrate) {
            $this->line($migrate->id . ' [' . $migrate->updated_at . ' ' . $migrate->status . '] : ' . $migrate->name);
        }
        return;
    }
}
