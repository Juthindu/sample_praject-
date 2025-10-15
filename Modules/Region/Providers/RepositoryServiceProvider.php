<?php
namespace Modules\Region\Providers;
use Illuminate\Support\ServiceProvider;
use Modules\Region\Repositories\Interfaces\RegionRepositoryInterface;
use Modules\Region\Repositories\RegionRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(RegionRepositoryInterface::class, RegionRepository::class);
    }

    public function boot()
    {
        
    }
}