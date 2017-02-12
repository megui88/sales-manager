<?php

namespace App\Console\Commands;

use App\Migrate;
use App\Sale;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class ActionMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sm:action-migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete/Annul migrate by id';

    public function configure()
    {
        $this->addOption('id', null, InputOption::VALUE_REQUIRED, 'id is required');
        $this->addOption('action', null, InputOption::VALUE_REQUIRED, 'action is required delete/annul');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->option('id');
        if (empty($id)){
            $this->error(PHP_EOL.'use --id to migration'.PHP_EOL);
            exit(2);
        }
        $action = $this->option('action');
        if (empty($action)){
            $this->error(PHP_EOL.'use --action to action'.PHP_EOL);
            exit(2);
        }
        $migrate = Migrate::where('id', '=', $id)->first();
        if(! $migrate) {
            $this->error('Migrate not found');
            exit(2);
        }
        /** @var Sale[] $sales */
        $sales = Sale::where('migrate_id', '=', $migrate->id)->get();
        foreach ($sales as $sale){
            switch ($action){
                case Migrate::DELETE:
                    Sale::where('migrate_id', '=', $migrate->id)->delete();
                    break;
                case Migrate::ANNUL:
                    $sale->update(['state' => Sale::ANNULLED]);
                    break;
            }
        }
        if(in_array($action, [Migrate::DELETE,Migrate::ANNUL])) {
            $migrate->update(['status' => $action]);
        }
        return;
    }
}
