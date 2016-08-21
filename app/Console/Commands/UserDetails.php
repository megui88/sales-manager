<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class UserDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sm:user-details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable/Disable User by code';

    public function configure()
    {
        $this->addOption('code', null, InputOption::VALUE_REQUIRED, 'Code is required');
        $this->addOption('id', null, InputOption::VALUE_REQUIRED, 'ID is required');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $code = $this->option('code');
        $id = $this->option('id');
        if (empty($code) && empty($id)){
            $this->error(PHP_EOL.'use --code or --id to find user'.PHP_EOL);
            exit(2);
        }

        $user = User::where('code', '=', $code)->orWhere('id', '=', $id)->first();
        if(! $user) {
            $this->error('User not found');
            exit(2);
        }
        dd($user->toArray());
        return;
    }
}
