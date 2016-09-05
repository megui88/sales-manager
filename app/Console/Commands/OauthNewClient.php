<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\OauthClients;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Input\InputOption;

class OauthNewClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:new-client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create OAuth Client';

    public function configure()
    {
        $this->addOption('name', null, InputOption::VALUE_REQUIRED, 'Name is required');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->option('name');
        if (empty($name)){
            $this->error(PHP_EOL.'use --name to assign name to client'.PHP_EOL);
            exit(2);
        }
        $data = [
            'name' => $name,
            'id' => sha1($name),
            'secret' => sha1(Uuid::uuid1())
        ];

        OauthClients::create($data);

        $this->comment(PHP_EOL . 'Client created');
        $this->table(
            ['Name', 'Id', 'Secret'],
            [
                [ $data['name'], $data['id'], $data['secret'] ],
            ]);
        return;
    }
}
