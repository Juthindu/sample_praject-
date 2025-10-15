<?php
namespace Modules\OwnResourceSample\Providers;
use Illuminate\Support\ServiceProvider;
use Modules\OwnResourceSample\Repositories\Interfaces\OwnSampleRepositoryInterface;
use Modules\OwnResourceSample\Repositories\OwnSampleRepository;
use Modules\OwnResourceSample\Repositories\OwnSampleDataRepository;
use Modules\OwnResourceSample\Repositories\Interfaces\OwnSampleDataRepositoryInterface;
use Modules\OwnResourceSample\Repositories\Interfaces\OwnSampleTestRepositoryInterface;
use Modules\OwnResourceSample\Repositories\OwnSampleTestRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(OwnSampleRepositoryInterface::class, OwnSampleRepository::class);
        $this->app->bind(OwnSampleDataRepositoryInterface::class, OwnSampleDataRepository::class);
        $this->app->bind(OwnSampleTestRepositoryInterface::class, OwnSampleTestRepository::class);

    }

    public function boot()
    {
        
    }
}