<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class MemberMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sm:member-migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable/Disable User by code';

    public function configure()
    {
        $this->addOption('code', null, InputOption::VALUE_REQUIRED, 'Code is required');
        $this->addOption('name', null, InputOption::VALUE_REQUIRED, 'Name is required');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $code = $this->option('code');
        $name = $this->option('name');
        if (empty($code) || empty($name)){
            $this->error(PHP_EOL.'use --code and --name to find user'.PHP_EOL);
            exit(2);
        }

        $user = User::createByCodeAndName($code, $name);
        if(! $user) {
            $this->error('User not work');
            exit(2);
        }
        dd($user->toArray());
        return;
    }
}
