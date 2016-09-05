<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class EnableUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sm:enable-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable/Disable User by code';

    public function configure()
    {
        $this->addOption('code', null, InputOption::VALUE_REQUIRED, 'Code is required');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $code = $this->option('code');
        if (empty($code)){
            $this->error(PHP_EOL.'use --code to enable/disable user'.PHP_EOL);
            exit(2);
        }

        $user = User::where('code', '=', $code)->first();
        if(! $user) {
            $this->error('User not found');
            exit(2);
        }
        $q =  $user->enable ? 'Disable user' : 'Enable user';
        $question = $this->confirm($q . '?', true);
        if($question){
            $user->enable = !$user->enable;
            $user->save();
        }
        return;
    }
}
