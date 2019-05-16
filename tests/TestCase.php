<?php

namespace LBHurtado\Missive\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use LBHurtado\Missive\Facades\Missive;
use LBHurtado\Missive\MissiveServiceProvider;

class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        include_once __DIR__.'/../database/migrations/create_s_m_s_s_table.php.stub';
        (new \CreateSMSsTable)->up();
    }

    protected function getPackageProviders($app)
    {
        return [
            MissiveServiceProvider::class
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
    }
}
