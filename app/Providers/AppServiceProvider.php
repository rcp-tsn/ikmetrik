<?php

namespace App\Providers;

use App\Models\CrmSupport;
use App\Models\SgkCompany;
use App\Observers\SgkCompanyObserver;
use App\Observers\CrmSupportObserver;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
//            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
//            $this->app->register(\Laracasts\Generators\GeneratorsServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Carbon::setLocale(app()->getLocale());
        SgkCompany::observe(SgkCompanyObserver::class);
        CrmSupport::observe(CrmSupportObserver::class);
    }
}
