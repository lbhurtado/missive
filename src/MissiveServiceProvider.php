<?php

namespace LBHurtado\Missive;

use Opis\Events\EventDispatcher;
use LBHurtado\Missive\Routing\Router;
use Illuminate\Support\ServiceProvider;
use LBHurtado\Missive\Models\{SMS, Contact, Relay};
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use LBHurtado\Missive\Repositories\{SMSRepository, SMSRepositoryEloquent};
use LBHurtado\Missive\Observers\{SMSObserver, ContactObserver, RelayObserver};
use LBHurtado\Missive\Repositories\{RelayRepository, RelayRepositoryEloquent};
use LBHurtado\Missive\Repositories\{ContactRepository, ContactRepositoryEloquent};

class MissiveServiceProvider extends ServiceProvider
{
    const APPLICATION_ROUTE_SMS = 'routes/sms.php';
    const PACKAGE_ROUTE_API = __DIR__.'/../routes/api.php';
    const PACKAGE_ROUTE_SMS = __DIR__.'/../routes/sms.php';
    const PACKAGE_FACTORY_DIR = __DIR__ . '/../database/factories';
    const PACKAGE_MISSIVE_CONFIG = __DIR__.'/../config/config.php';
    const PACKAGE_TACTICIAN_FIELDS_CONFIG = __DIR__.'/../config/tactician.fields.php';
    const PACKAGE_SMSS_TABLE_MIGRATION_STUB = __DIR__.'/../database/migrations/create_s_m_s_s_table.php.stub';
    const PACKAGE_RELAYS_TABLE_MIGRATION_STUB = __DIR__.'/../database/migrations/create_relays_table.php.stub';
    const PACKAGE_CONTACTS_TABLE_MIGRATION_STUB = __DIR__.'/../database/migrations/create_contacts_table.php.stub';

    public function boot()
    {
        $this->observeModels();
        $this->registerConfigs();
        $this->publishMigrations();
        $this->publishRoutes();
        $this->app->make(EloquentFactory::class)->load(self::PACKAGE_FACTORY_DIR);
        $this->mapRoutes();
    }

    public function register()
    {
        $this->registerConfigs();
        $this->registerRepositories();
        $this->registerModels();
        $this->registerFacades();
        $this->registerClasses();
    }

    protected function observeModels()
    {
        app('missive.sms')::observe(SMSObserver::class);
        app('missive.relay')::observe(RelayObserver::class);
        app('missive.contact')::observe(ContactObserver::class);
    }

    protected function publishConfigs()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('missive.php'),
            ], 'config');
        }
    }

    protected function publishMigrations()
    {
        if ($this->app->runningInConsole()) {
            if (! class_exists(CreateRelaysTable::class)) {
                $this->publishes([
                    self::PACKAGE_RELAYS_TABLE_MIGRATION_STUB => database_path('migrations/'.date('Y_m_d_His', time()).'_create_relays_table.php'),
                ], 'migrations');
            }
            if (! class_exists(CreateSMSsTable::class)) {
                $this->publishes([
                    self::PACKAGE_SMSS_TABLE_MIGRATION_STUB => database_path('migrations/'.date('Y_m_d_His', time()).'_create_s_m_s_s_table.php'),
                ], 'migrations');
            }
            if (! class_exists(CreateContactsTable::class)) {
                $this->publishes([
                    self::PACKAGE_CONTACTS_TABLE_MIGRATION_STUB => database_path('migrations/'.date('Y_m_d_His', time()).'_create_contacts_table.php'),
                ], 'migrations');
            }
        }
    }

    protected function publishRoutes()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::PACKAGE_ROUTE_SMS => base_path(self::APPLICATION_ROUTE_SMS),
            ], 'routes');
        }
    }

    protected function registerConfigs()
    {
        $this->mergeConfigFrom(self::PACKAGE_MISSIVE_CONFIG, 'missive');
        $this->mergeConfigFrom(self::PACKAGE_TACTICIAN_FIELDS_CONFIG, 'tactician.fields');
    }

    protected function registerRepositories()
    {
        $this->app->bind(SMSRepository::class, SMSRepositoryEloquent::class);
        $this->app->bind(RelayRepository::class, RelayRepositoryEloquent::class);
        $this->app->bind(ContactRepository::class, ContactRepositoryEloquent::class);
    }

    protected function registerModels()
    {
        $this->app->singleton('missive.sms', function () {
            $class = config('missive.classes.models.sms', SMS::class);
            return new $class;
        });
        $this->app->singleton('missive.relay', function () {
            $class = config('missive.classes.models.relay', Relay::class);
            return new $class;
        });
        $this->app->singleton('missive.contact', function () {
            $class = config('missive.classes.models.contact', Contact::class);
            return new $class;
        });
    }

    protected function registerFacades()
    {
        $this->app->singleton('missive', function () {
            return new Missive;
        });
        $this->app->singleton('missive:router', function () {
            return new Router(app(Missive::class));
        });
    }

    protected function registerClasses()
    {
        $this->app->singleton(Router::class);
        $this->app->singleton(EventDispatcher::class);
        $this->app->singleton(Missive::class, function ($app) {
            return $app->make('missive');
        });
        $this->app->singleton(Router::class, function ($app) {
            return $app->make('missive:router');
        });
    }

    public function mapRoutes()
    {
        $this->loadRoutesFrom(self::PACKAGE_ROUTE_API);
        $file = base_path(self::APPLICATION_ROUTE_SMS);
        if (file_exists($file)) include_once $file;
    }
}
