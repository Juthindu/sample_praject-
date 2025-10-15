<?php
namespace Modules\ConsumerSample\Providers;
use Illuminate\Support\ServiceProvider;
use Modules\ConsumerSample\Repositories\Interfaces\ConsumerSampleRepositoryInterface;
use Modules\ConsumerSample\Repositories\ConsumerSampleRepository;
use Modules\ConsumerSample\Repositories\ConsumerSampleDataRepository;
use Modules\ConsumerSample\Repositories\ConsumerSampleTestRepository;
use Modules\ConsumerSample\Repositories\Interfaces\ConsumerSampleDataRepositoryInterface;
use Modules\ConsumerSample\Repositories\Interfaces\ConsumerSampleTestRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ConsumerSampleRepositoryInterface::class, ConsumerSampleRepository::class);
        $this->app->bind(ConsumerSampleDataRepositoryInterface::class, ConsumerSampleDataRepository::class);
        $this->app->bind(ConsumerSampleTestRepositoryInterface::class, ConsumerSampleTestRepository::class);

    }

    public function boot()
    {
        
    }
}