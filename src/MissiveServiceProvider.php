<?php

namespace LBHurtado\Missive;

use Opis\Events\EventDispatcher;
use Illuminate\Support\ServiceProvider;
use LBHurtado\Missive\Observers\SMSObserver;
use LBHurtado\Missive\Models\{SMS, Contact, Relay};
use LBHurtado\Missive\Repositories\{SMSRepository, SMSRepositoryEloquent};
use LBHurtado\Missive\Repositories\{RelayRepository, RelayRepositoryEloquent};
use LBHurtado\Missive\Repositories\{ContactRepository, ContactRepositoryEloquent};

class MissiveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        config('missive.classes.relay', SMS::class)::observe(SMSObserver::class);

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

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
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->registerConfigs();
        $this->registerRepositories();
        $this->registerModels();
        $this->registerFacades();
        $this->registerClasses();
    }

    protected function registerConfigs()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'missive');
        $this->mergeConfigFrom(__DIR__.'/../config/tactician.fields.php', 'tactician.fields');
    }

    protected function registerRepositories()
    {
        $this->app->bind(SMSRepository::class, SMSRepositoryEloquent::class);
        $this->app->bind(RelayRepository::class, RelayRepositoryEloquent::class);
        $this->app->bind(ContactRepository::class, ContactRepositoryEloquent::class);
    }

    protected function registerModels()
    {
        $this->app->singleton('missive.contact', function () {
            $class = config('missive.classes.models.contact', Contact::class);
            return new $class;
        });
        $this->app->singleton('missive.relay', function () {
            $class = config('missive.classes.models.relay', Relay::class);
            return new $class;
        });
        $this->app->singleton('missive.sms', function () {
            $class = config('missive.classes.models.sms', SMS::class);
            return new $class;
        });
    }

    protected function registerFacades()
    {
        $this->app->singleton('missive', function () {
            return new Missive(app(SMSRepository::class));
        });
    }

    protected function registerClasses()
    {
        $this->app->singleton(EventDispatcher::class);
        $this->app->singleton(Missive::class, function ($app) {
            return $app->make('missive');
        });
    }
}
