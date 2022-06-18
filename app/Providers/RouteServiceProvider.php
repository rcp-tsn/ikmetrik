<?php

namespace App\Providers;

use App\Models\CrmSupport;
use App\Models\Customer;
use App\Models\CustomerEmail;
use App\Models\Department;
use App\Models\Egn;
use App\Models\Job;
use App\Models\Packet;
use App\Models\Sector;
use App\Models\Company;
use App\Models\SgkCompany;
use App\Models\WorkTitle;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use App\User;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
        Route::bind('permission', function($hashId, $route) {
            return Permission::findByHashOrFail($hashId);
        });
        Route::bind('role', function($hashId, $route) {
            return Role::findByHashOrFail($hashId);
        });

        Route::bind('user', function($hashId, $route) {
            return User::findByHashOrFail($hashId);
        });

        Route::bind('sector', function($hashId, $route) {
            return Sector::findByHashOrFail($hashId);
        });
        Route::bind('egn', function($hashId, $route) {
            return Egn::findByHashOrFail($hashId);
        });
        Route::bind('packet', function($hashId, $route) {
            return Packet::findByHashOrFail($hashId);
        });

        Route::bind('company', function($hashId, $route) {
            return Company::findByHashOrFail($hashId);
        });

        Route::bind('job', function($hashId, $route) {
            return Job::findByHashOrFail($hashId);
        });

        Route::bind('work_title', function($hashId, $route) {
            return WorkTitle::findByHashOrFail($hashId);
        });

        Route::bind('department', function($hashId, $route) {
            return Department::findByHashOrFail($hashId);
        });

        Route::bind('sgk_company', function($hashId, $route) {
            return SgkCompany::findByHashOrFail($hashId);
        });

        Route::bind('crm_support', function($hashId, $route) {
            return CrmSupport::findByHashOrFail($hashId);
        });


    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */

    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();

        $this->mapAdminRoutes();

        $this->mapUserRoutes();

        $this->mapOtherRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::middleware(['web', 'user', 'role:Admin|Owner'])
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }

    /**
     * Define the "user" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapUserRoutes()
    {
        Route::middleware(['web', 'user'])
            ->namespace($this->namespace)
            ->group(base_path('routes/user.php'));
    }

    /**
     * Define the "other" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapOtherRoutes()
    {
        Route::middleware(['web', 'user'])
            ->namespace($this->namespace)
            ->group(base_path('routes/other.php'));
    }
}
