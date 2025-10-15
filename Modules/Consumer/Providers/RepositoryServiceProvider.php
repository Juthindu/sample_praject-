<?php
namespace Modules\Consumer\Providers;
use Illuminate\Support\ServiceProvider;
use Modules\Consumer\Repositories\Interfaces\ConsumerRepositoryInterface;
use Modules\Consumer\Repositories\ConsumerRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ConsumerRepositoryInterface::class, ConsumerRepository::class);
    }

    public function boot()
    {
        
    }
}