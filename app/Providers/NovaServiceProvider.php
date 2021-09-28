<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\Transaction;
use App\Nova\Metrics\Earnings;
use App\Nova\Metrics\TotalNumberOfPendingTransaction;
use App\Nova\Metrics\TotalNumberOfTransaction;
use Coroowicaksono\ChartJsIntegration\BarChart;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        \OptimistDigital\NovaSettings\NovaSettings::addSettingsFields([
            Image::make('Logo'),

            Text::make('System Name')
                ->rules(['required', 'max:10']),
        ]);
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return true;
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        $transactionsDaily = Transaction::get()->groupBy(function ($transaction) {
            return $transaction->created_at->format('m/d/y');
        });

        $data = [];
        $labels = [];

        foreach ($transactionsDaily as $key => $transaction) {
            $data[] = count($transaction);
            $labels[] = $key;
        }
        return [
            TotalNumberOfPendingTransaction::make(),
            TotalNumberOfTransaction::make(),
            Earnings::make(),
            (new BarChart())
                ->title('Transactions')
                ->animations([
                    'enabled' => true,
                    'easing' => 'easeinout',
                ])
                ->series(array([
                    'barPercentage' => 0.5,
                    'label' => 'Trasanctions',
                    'backgroundColor' => 'blue',
                    'data' => $data
                ]))
                ->options([
                    'xaxis' => [
                        'categories' => $labels
                    ],
                ])
                ->width('full'),
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            (new \OptimistDigital\NovaSettings\NovaSettings)->canSee(function(){
                return auth()->user()->hasRole(Role::ROLE_SUPER_ADMIN);
            }),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
