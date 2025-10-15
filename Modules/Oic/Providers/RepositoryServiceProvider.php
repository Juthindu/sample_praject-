<?php
namespace Modules\Oic\Providers;
use Illuminate\Support\ServiceProvider;
use Modules\Oic\Repositories\Interfaces\OicRepositoryInterface;
use Modules\Oic\Repositories\OicRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(OicRepositoryInterface::class, OicRepository::class);
    }

    public function boot()
    {
        
    }
}