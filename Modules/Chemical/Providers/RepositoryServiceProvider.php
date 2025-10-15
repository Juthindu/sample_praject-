<?php
namespace Modules\Chemical\Providers;
use Illuminate\Support\ServiceProvider;
use Modules\Chemical\Repositories\AdjustmentRepository;
use Modules\Chemical\Repositories\Interfaces\ChemicalRepositoryInterface;
use Modules\Chemical\Repositories\ChemicalRepository;
use Modules\Chemical\Repositories\Interfaces\AdjustmentRepositoryInterface;
use Modules\Chemical\Repositories\Interfaces\StockRepositoryInterface;
use Modules\Chemical\Repositories\StockRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ChemicalRepositoryInterface::class, ChemicalRepository::class);
        $this->app->bind(StockRepositoryInterface::class, StockRepository::class);
        $this->app->bind(AdjustmentRepositoryInterface::class, AdjustmentRepository::class);
    }

    public function boot()
    {
        
    }
}