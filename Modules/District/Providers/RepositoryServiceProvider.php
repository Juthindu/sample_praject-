<?php
namespace Modules\District\Providers;
use Illuminate\Support\ServiceProvider;
use Modules\District\Repositories\Interfaces\DistrictRepositoryInterface;
use Modules\District\Repositories\DistrictRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(DistrictRepositoryInterface::class, DistrictRepository::class);
    }

    public function boot()
    {
        
    }
}