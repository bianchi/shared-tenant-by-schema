<?php

declare(strict_types=1);

namespace Tests;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\ParallelTesting;

trait RefreshDatabaseWithTenant
{
    use RefreshDatabase {
        beginDatabaseTransaction as parentBeginDatabaseTransaction;
    }

    /**
     * The database connections that should have transactions.
     *
     * `null` is the default landlord connection, used for system-wide operations.
     * `tenant` is the tenant connection, specific to each tenant in the multi-tenant system.
     */
    protected array $connectionsToTransact = [null, 'tenant'];

    /**
     * We need to hook initialize tenancy _before_ we start the database
     * transaction, otherwise it cannot find the tenant connection.
     * This function initializes the tenant setup before starting a transaction.
     */
    public function beginDatabaseTransaction()
    {
        // Initialize tenant before beginning the database transaction.
        $this->initializeTenant();

        // Continue with the default database transaction setup.
        $this->parentBeginDatabaseTransaction();
    }

    /**
     * Initialize tenant for testing environment.
     * This function sets up a specific tenant for testing purposes.
     */
    public function initializeTenant()
    {
        // Hardcoded tenant ID for testing purposes.
        $tenantId = 'foo';

        // Retrieve or create the tenant with the given ID.
        $tenant = Tenant::firstOr(function () use ($tenantId) {
            /**
             * Set the tenant prefix to the parallel testing token.
             * This is necessary to avoid database collisions when running tests in parallel.
             */
            config(['tenancy.database.prefix' => config('tenancy.database.prefix').ParallelTesting::token()]);

            // Define the database name for the tenant.
            $dbName = config('tenancy.database.prefix').$tenantId;

            // Drop the database if it already exists.
            DB::unprepared("DROP SCHEMA IF EXISTS $dbName CASCADE");

            // Create the tenant and associated domain if they don't exist.
            $t = Tenant::create(['id' => $tenantId]);
            if (! $t->domains()->count()) {
                $t->domains()->create(['domain' => $tenantId.'.localhost']);
            }

            return $t;
        });

        // Initialize tenancy for the current test.
        tenancy()->initialize($tenant);

        $this->app['config']->set('session.domain', $tenant->id.'.localhost');
        $this->app['url']->forceRootUrl('http://'.$tenant->id.'.localhost');
    }
}
