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
        Sale::saved($this->setTransaction());
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
            'charges' => \App\Charge::class,
            'debits' => \App\Debit::class,
            'dues' => \App\Due::class,
            'payments' => \App\Payment::class,
        ]);
    }

    private function setTransaction()
    {
        return function($entity){

            if($entity instanceof Transactional){
                /** @var Transaction $transaction */
                $transaction = $entity->transaction()->create([]);
                $transaction->setAttribute('client_id', 1);  //session client
                $transaction->setAttribute('office_id', 2);  //session office
                $transaction->setAttribute('operator_id', 3);  //session user
                $transaction->setAttribute('supervisor_id', null); //session supervisor
                $transaction->setAttribute('payer_id', $entity->getPayerId());
                $transaction->setAttribute('collector_id', $entity->getCollectorId());
                if($entity instanceof States){
                    $transaction->setAttribute('state', $entity->getState());
                }
                if($entity instanceof Commentable){
                    $transaction->setAttribute('comment_id', $entity->comment->getId);
                }
                $transaction->save();
            }
            return true;

        };
    }
}
