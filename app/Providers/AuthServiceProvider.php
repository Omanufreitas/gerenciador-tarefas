<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Tarefa;
use App\Policies\TarefaPolicy;

class AuthServiceProvider extends ServiceProvider
{
   
    protected $policies = [
        Tarefa::class => TarefaPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();


    }
}
