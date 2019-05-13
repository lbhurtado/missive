<?php

namespace LBHurtado\Missive;

use LBHurtado\Missive\Models\SMS;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use LBHurtado\Missive\Observers\SMSObserver;
use LBHurtado\Missive\Repositories\{SMSRepository, SMSRepositoryEloquent};
use LBHurtado\Missive\Repositories\{RelayRepository, RelayRepositoryEloquent};
use LBHurtado\Missive\Repositories\{ContactRepository, ContactRepositoryEloquent};
use LBHurtado\Missive\Actions\CreateSMSAction;



class MissiveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        SMS::observe(SMSObserver::class);

        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'missive');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'missive');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('missive.php'),
            ], 'config');

            // Publishing the migrations.
            if (! class_exists(CreateSMSsTable::class)) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_s_m_s_s_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_s_m_s_s_table.php'),
                ], 'migrations');                
            }
            if (! class_exists(CreateContactsTable::class)) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_contacts_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_contacts_table.php'),
                ], 'migrations');                
            }
            if (! class_exists(CreateRelaysTable::class)) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_relays_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_relays_table.php'),
                ], 'migrations');                
            }

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/missive'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/missive'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/missive'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }

        Route::prefix('api')
             ->middleware('api')
             ->match(['get', 'post'], 'sms/relay', CreateSMSAction::class);

//        Route::post('webhook/sms', App\Missive\Actions\CreateSMSAction::class);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'missive');

        // Register the main class to use with the facade
        $this->app->singleton('missive', function () {
            return new Missive;
        });

        $this->app->bind(SMSRepository::class, SMSRepositoryEloquent::class);
        $this->app->bind(RelayRepository::class, RelayRepositoryEloquent::class);
        $this->app->bind(ContactRepository::class, ContactRepositoryEloquent::class);
    }
}
