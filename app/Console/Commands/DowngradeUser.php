<?php

namespace App\Console\Commands;

use App\Services\BusinessCore;
use App\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class DowngradeUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sm:downgrade-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downgrade User by code';

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
            $this->error(PHP_EOL.'use --code to downgrade user'.PHP_EOL);
            exit(2);
        }

        $user = User::where('code', '=', $code)->first();
        if(! $user) {
            $this->error('User not found');
            exit(2);
        }
        $role =  $user->role;
        switch ( $user->role){
            case BusinessCore::VENDOR_ROLE:
            case BusinessCore::MEMBER_ROLE:
                $this->error(PHP_EOL.'the user is '.$role.PHP_EOL);
                exit(2);
                break;
            case BusinessCore::EMPLOYEE_ROLE:
                $role = BusinessCore::MEMBER_ROLE;
                $q = "Downgrade to $role?";
                break;
            case BusinessCore::PHARMACIST_ROLE:
                $role = BusinessCore::EMPLOYEE_ROLE;
                $q = "Downgrade to $role?";
                break;
            case BusinessCore::EMPLOYEE_ADMIN_ROLE:
                $role = BusinessCore::PHARMACIST_ROLE;
                $q = "Downgrade to $role?";
                break;
        }
        $question = $this->confirm($q, true);
        if($question){
            $user->role = $role;
            $user->save();
        }
        return;
    }
}
