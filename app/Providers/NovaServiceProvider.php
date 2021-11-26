<?php

namespace App\Providers;

use App\Models\Role;
use Laravel\Nova\Nova;
use App\Models\Transaction;
use Laravel\Nova\Fields\Text;
use App\Nova\Metrics\Earnings;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Textarea;
use Illuminate\Support\Facades\Gate;
use App\Nova\Metrics\TotalNumberOfTransaction;
use Coroowicaksono\ChartJsIntegration\BarChart;
use Coroowicaksono\ChartJsIntegration\PieChart;
use Laravel\Nova\NovaApplicationServiceProvider;
use App\Nova\Metrics\TotalNumberOfPendingTransaction;
use Coroowicaksono\ChartJsIntegration\LineChart;

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

            Number::make('Fee (%)', 'fee')
                ->rules(['required']),
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
        $transactionsDaily = Transaction::orderBy('created_at')->get()->groupBy(function ($transaction) {
            return $transaction->created_at->format('m/d/y');
        });

        $data = [];
        $labels = [];

        foreach ($transactionsDaily as $key => $transaction) {
            $data[] = count($transaction);
            $labels[] = $key;
        }

        $transactionsMonthly = Transaction::orderBy('created_at')->get()->groupBy(function ($transaction) {
            return $transaction->created_at->format('m/Y');
        });

        $dataM = [];
        $labelsM = [];

        foreach ($transactionsMonthly as $key => $transaction) {
            $dataM[] = count($transaction);
            $labelsM[] = $key;
        }

        $transactionsYearly = Transaction::orderBy('created_at')->get()->groupBy(function ($transaction) {
            return $transaction->created_at->format('Y');
        });

        $dataY = [];
        $labelsY = [];

        foreach ($transactionsYearly as $key => $transaction) {
            $dataY[] = count($transaction);
            $labelsY[] = $key;
        }

        return [
            TotalNumberOfPendingTransaction::make()->width('1/2'),
            TotalNumberOfTransaction::make()->width('1/2'),
            Earnings::make()->width('1/2'),
            (new BarChart())
                ->title('Daily Transaction')
                ->animations([
                    'enabled' => true,
                    'easing' => 'easeinout',
                ])
                ->series(array([
                    'barPercentage' => 0.5,
                    'label' => 'Trasanctions',
                    'backgroundColor' => '#222',
                    'data' => $data
                ]))
                ->options([
                    'xaxis' => [
                        'categories' => $labels
                    ],
                ])
                ->width('full'),
            (new LineChart())
                ->title('Monthly Transaction')
                ->animations([
                    'enabled' => true,
                    'easing' => 'easeinout',
                ])
                ->series(array([
                    'barPercentage' => 0.5,
                    'label' => 'Trasanctions',
                    'backgroundColor' => '#222',
                    'data' => $dataM
                ]))
                ->options([
                    'xaxis' => [
                        'categories' => $labelsM
                    ],
                ])
                ->width('full'),
                (new LineChart())
                ->title('Yearly Transaction')
                ->animations([
                    'enabled' => true,
                    'easing' => 'easeinout',
                ])
                ->series(array([
                    'barPercentage' => 0.5,
                    'label' => 'Trasanctions',
                    'backgroundColor' => '#222',
                    'data' => $dataY
                ]))
                ->options([
                    'xaxis' => [
                        'categories' => $labelsY
                    ],
                ])
                ->width('full'),
                (new PieChart())
                    ->title('Transactions')
                    ->series(array([
                        'data' => [Transaction::whereStatus('pending')->count(), Transaction::where('status', '!=', 'pending')->count()],
                        'backgroundColor' => ["#555","#222",],
                    ]))
                    ->options([
                        'xaxis' => [
                            'categories' => ['Pending', 'Completed'],
                        ],
                    ])->width('1/2'),

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
            (new \OptimistDigital\NovaSettings\NovaSettings)->canSee(function () {
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
