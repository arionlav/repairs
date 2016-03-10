<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ExampleServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        //$_SESSION['prov'] = 'this will be called at bootstrapping application, before EventServicesProvider';
    }
}