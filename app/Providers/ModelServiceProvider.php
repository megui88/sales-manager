<?php

namespace App\Providers;

use App\Contract\Commentable;
use App\Contract\States;
use App\Contract\Transactional;
use App\Http\Requests\Request;
use App\Sale;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class ModelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootMorphMap();
        Sale::created($this->setTransaction());
        Sale::deleted($this->setTransaction());
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function bootMorphMap()
    {
        Relation::morphMap([
            'sales' => \App\Sale::class,
            'accredits' => \App\Accredit::class,
            'dues' => \App\Due::class,
        ]);
    }

    private function setTransaction()
    {
        return function ($entity){

            if ($entity instanceof Transactional) {

                /* @var Transaction $transaction */
                $data = [
                    'client_id' => 1,
                    'office_id' => 2,
                    'operator_id' => 3,
                    'supervisor_id' => null,
                    'payer_id' => $entity->payer_id,
                    'collector_id' => $entity->collector_id,

                ];
                if ($entity instanceof States) {
                    $data['state'] = $entity->getState();
                }
                if ($entity instanceof Commentable) {
                    $data['comment_id'] = $entity->comment->getId;
                }
                $entity->transaction()->create($data);
            }

            return true;

        };
    }
}
