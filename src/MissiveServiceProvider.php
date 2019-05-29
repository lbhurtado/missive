<?php

namespace LBHurtado\Missive;

use Opis\Events\EventDispatcher;
use LBHurtado\Missive\Routing\Router;
use Illuminate\Support\ServiceProvider;
use LBHurtado\Missive\Models\{SMS, Contact, Relay, Airtime, Topup};
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use LBHurtado\Missive\Repositories\{SMSRepository, SMSRepositoryEloquent};
use LBHurtado\Missive\Repositories\{RelayRepository, RelayRepositoryEloquent};
use LBHurtado\Missive\Repositories\{ContactRepository, ContactRepositoryEloquent};
use LBHurtado\Missive\Repositories\{AirtimeRepository, AirtimeRepositoryEloquent};
use LBHurtado\Missive\Observers\{SMSObserver, ContactObserver, RelayObserver, AirtimeObserver,
    AirtimeContactObserver};

class MissiveServiceProvider extends ServiceProvider
{
    const APPLICATION_ROUTE_SMS = 'routes/sms.php';
    const APPLICATION_AIRTIME_SEEDER = 'seeds/AirtimeSeeder.php';

    const PACKAGE_ROUTE_API = __DIR__.'/../routes/api.php';
    const PACKAGE_ROUTE_SMS = __DIR__.'/../routes/sms.php';
    const PACKAGE_FACTORY_DIR = __DIR__ . '/../database/factories';
    const PACKAGE_MISSIVE_CONFIG = __DIR__.'/../config/config.php';
    const PACKAGE_AIRTIME_SEEDER = __DIR__.'/../database/seeds/AirtimeSeeder.php';
    const PACKAGE_TACTICIAN_FIELDS_CONFIG = __DIR__ . '/../config/tactician.fields.php';
    const PACKAGE_SMSS_TABLE_MIGRATION_STUB = __DIR__.'/../database/migrations/create_s_m_s_s_table.php.stub';
    const PACKAGE_RELAYS_TABLE_MIGRATION_STUB = __DIR__.'/../database/migrations/create_relays_table.php.stub';
    const PACKAGE_CONTACTS_TABLE_MIGRATION_STUB = __DIR__.'/../database/migrations/create_contacts_table.php.stub';
    const PACKAGE_AIRTIMES_TABLE_MIGRATION_STUB = __DIR__.'/../database/migrations/create_airtimes_table.php.stub';
    const PACKAGE_TOPUPS_TABLE_MIGRATION_STUB = __DIR__.'/../database/migrations/create_topups_table.php.stub';

    public function boot()
    {
        $this->observeModels();
        $this->publishConfigs();
        $this->publishMigrations();
        $this->publishSeeds();
        $this->publishRoutes();
        $this->mapFactories();
        $this->mapRoutes();
    }

    public function register()
    {
        $this->registerConfigs();
        $this->registerRepositories();
        $this->registerModels();
        $this->registerPivots();
        $this->registerFacades();
        $this->registerClasses();
    }

    protected function observeModels()
    {
        app('missive.sms')::observe(SMSObserver::class);
        app('missive.relay')::observe(RelayObserver::class);
        app('missive.contact')::observe(ContactObserver::class);
        app('missive.airtime')::observe(AirtimeObserver::class);
        app('missive.airtime_contact')::observe(AirtimeContactObserver::class);
        //TODO: create topup observer
    }

    protected function publishConfigs()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::PACKAGE_MISSIVE_CONFIG => config_path('missive.php'),
            ], 'missive-config');
        }
    }

    protected function publishMigrations()
    {
        if ($this->app->runningInConsole()) {
            if (! class_exists(CreateRelaysTable::class)) {
                $this->publishes([
                    self::PACKAGE_RELAYS_TABLE_MIGRATION_STUB => database_path('migrations/'.date('Y_m_d_His', time()).'_create_relays_table.php'),
                ], 'missive-migrations');
            }
            if (! class_exists(CreateSMSsTable::class)) {
                $this->publishes([
                    self::PACKAGE_SMSS_TABLE_MIGRATION_STUB => database_path('migrations/'.date('Y_m_d_His', time()).'_create_s_m_s_s_table.php'),
                ], 'missive-migrations');
            }
            if (! class_exists(CreateContactsTable::class)) {
                $this->publishes([
                    self::PACKAGE_CONTACTS_TABLE_MIGRATION_STUB => database_path('migrations/'.date('Y_m_d_His', time()).'_create_contacts_table.php'),
                ], 'missive-migrations');
            }
            if (! class_exists(CreateAirtimesTable::class)) {
                $this->publishes([
                    self::PACKAGE_AIRTIMES_TABLE_MIGRATION_STUB => database_path('migrations/'.date('Y_m_d_His', time()+60).'_create_airtimes_table.php'),
                ], 'missive-migrations');
            }
            if (! class_exists(CreateTopupsTable::class)) {
                $this->publishes([
                    self::PACKAGE_TOPUPS_TABLE_MIGRATION_STUB => database_path('migrations/'.date('Y_m_d_His', time()+60).'_create_topups_table.php'),
                ], 'missive-migrations');
            }
        }
    }

    protected function publishSeeds()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::PACKAGE_AIRTIME_SEEDER => database_path(self::APPLICATION_AIRTIME_SEEDER),
            ], 'missive-seeds');
        }
    }

    protected function publishRoutes()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::PACKAGE_ROUTE_SMS => base_path(self::APPLICATION_ROUTE_SMS),
            ], 'missive-routes');
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
        $this->app->bind(AirtimeRepository::class, AirtimeRepositoryEloquent::class);
        //TODO: create topup repository
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
        $this->app->singleton('missive.airtime', function () {
            $class = config('missive.classes.models.airtime', Airtime::class);
            return new $class;
        });
        $this->app->singleton('missive.topup', function () {
            $class = config('missive.classes.models.topup', Topup::class);
            return new $class;
        });
    }

    protected function registerPivots()
    {
        $this->app->singleton('missive.airtime_contact', function () {
            $class = config('missive.classes.pivots.airtime_contact', Airtime::class);
            return new $class;
        });
    }

    protected function registerFacades()
    {
        $this->app->singleton('missive', function () {
            return new Missive(app(AirtimeRepository::class));
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

    public function mapFactories()
    {
        $this->app->make(EloquentFactory::class)->load(self::PACKAGE_FACTORY_DIR);
    }

    public function mapRoutes()
    {
        $this->loadRoutesFrom(self::PACKAGE_ROUTE_API);
        $file = base_path(self::APPLICATION_ROUTE_SMS);
        if (file_exists($file)) include_once $file;
    }
}
