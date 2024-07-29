<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Repositories\GroupsRepository;
use App\Repositories\MoneyRepository;
use App\Models\Groups;
use App\Models\Money;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Sharing data with all views
        View::composer('*', function ($view) {

            // Resolve the repositories from the service container
            $groupsRepository = app(GroupsRepository::class);
            $moneyRepository = app(MoneyRepository::class);

            // Fetch the data
            $groups = $groupsRepository->getGroups();
            $arrayFondsByGroup = $moneyRepository->fonds($groups);
            $arrayGainByGroup = $moneyRepository->gains($groups);
            
            // Share the data with the view
            $view->with('sommeFondsByGroups', $arrayFondsByGroup)
                 ->with('tailleSommesFonds', count($arrayFondsByGroup))
                 ->with('sommeGainsByGroups', $arrayGainByGroup)
                 ->with('tailleSommesGains', count($arrayGainByGroup))
                 ;
        });
    }
}
