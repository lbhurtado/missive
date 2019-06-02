<?php

namespace LBHurtado\Missive\Tests;

use LBHurtado\Missive\Facades\Missive;
use Illuminate\Foundation\Testing\WithFaker;
use LBHurtado\Missive\MissiveServiceProvider;
use LBHurtado\Missive\Actions\CreateSMSAction;
use LBHurtado\Tactician\TacticianServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Propaganistas\LaravelPhone\PhoneServiceProvider;
use Spatie\SchemalessAttributes\SchemalessAttributesServiceProvider;
use Joselfonseca\LaravelTactician\Providers\LaravelTacticianServiceProvider;

class TestCase extends BaseTestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        include_once __DIR__.'/../database/migrations/create_relays_table.php.stub';
        include_once __DIR__.'/../database/migrations/create_s_m_s_s_table.php.stub';
        include_once __DIR__.'/../database/migrations/create_contacts_table.php.stub';
        include_once __DIR__.'/../database/migrations/create_airtimes_table.php.stub';
        include_once __DIR__.'/../database/migrations/create_topups_table.php.stub';

        (new \CreateSMSsTable)->up();
        (new \CreateRelaysTable)->up();
        (new \CreateContactsTable)->up();
        (new \CreateAirtimesTable)->up();
        (new \CreateTopupsTable)->up();

        include_once __DIR__.'/../database/seeds/AirtimeSeeder.php';
        (new \AirtimeSeeder)->run();

        require_once __DIR__.'/../routes/api.php';

        $this->faker = $this->makeFaker('en_PH');
    }

    protected function getPackageProviders($app)
    {
        return [
            MissiveServiceProvider::class,
            LaravelTacticianServiceProvider::class,
            SchemalessAttributesServiceProvider::class,
            TacticianServiceProvider::class,
            PhoneServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Missive' => Missive::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['router']->resource('api/sms/relay', CreateSMSAction::class);
        // $app['config']->set('missive.relay.default', 'telerivet');
    }

    public function array_replace_keys($data, $mapping)
    {
        $replaced = [];

        foreach ($mapping as $key => $value) {
            $replaced[$key] = $data[$value];
        }

        return $replaced;
    }
}
