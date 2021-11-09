<?php

namespace App\Nova;

use App\Nova\Actions\MarkAsComplete;
use App\Nova\Lenses\CompletedTransaction;
use App\Nova\Lenses\PendingTransaction;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Pdmfc\NovaCards\Info;
use Sixlive\TextCopy\TextCopy;

class Transaction extends Resource
{
    public static $group = "Menu";
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Transaction::class;


    public function title()
    {
        return "$this->reference_number - $this->status";
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'reference_number',
        'sender_first_name',
        'receiver_first_name'
    ];

    public function agentFields()
    {
        return [
            BelongsTo::make('Agent', 'agent', User::class)
            ->exceptOnForms(),
            Date::make('Completed At')
            ->exceptOnForms()
        ];
    }

    public function senderFields()
    {
        return [
            Text::make('Sender First Name')
                ->hideFromIndex()
                ->rules(['required']),

            Text::make('Sender Middle Name')
                ->hideFromIndex()
                ->rules(['required']),

            Text::make('Sender Last Name')
                ->hideFromIndex()
                ->rules(['required']),

            Text::make('Sender Address')
                ->hideFromIndex()
                ->rules(['required']),

            Text::make('Sender Email')
                ->hideFromIndex()
                ->rules(['required', 'email']),

            Text::make('Sender Mobile')
                ->hideFromIndex()
                ->rules(['required']),
        ];
    }

    public function receiverFields()
    {
        return [
            Text::make('Receiver First Name')
                ->hideFromIndex()
                ->rules(['required']),

            Text::make('Receiver Middle Name')
                ->hideFromIndex()
                ->rules(['required']),

            Text::make('Receiver Last Name')
                ->hideFromIndex()
                ->rules(['required']),

            Text::make('Receiver Address')
                ->hideFromIndex()
                ->rules(['required']),

            Text::make('Receiver Email')
                ->hideFromIndex()
                ->rules(['required', 'email']),

            Text::make('Receiver Mobile')
                ->hideFromIndex()
                ->rules(['required']),
        ];
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            TextCopy::make('Reference Number')
                ->exceptOnForms(),

            Text::make('Status')
                ->exceptOnForms(),

            Number::make('Amount')
                ->rules(['required'])
                ->step('0.1'),

            Number::make('Fee')
                ->exceptOnForms(),

            Date::make('Created At')
                ->sortable()
                ->exceptOnForms(),

            Text::make('Relationship')
                ->hideFromIndex()
                ->rules(['required']),

            Textarea::make('Purpose')
                ->alwaysShow()
                ->rules(['required']),

            new Panel('Sender Information', $this->senderFields()),

            new Panel('Receiver Information', $this->receiverFields()),

            new Panel('Agent Information', $this->agentFields())
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            (new Info())
                ->onlyOnDetail()
                ->success('Transaction is Available and ready to process!')
                ->canSee(function ($request) {
                    $transaction = \App\Models\Transaction::find($request->resourceId);
                    if (!$transaction) {
                        return;
                    }
                    return $transaction->status == 'pending';
                }),
            (new Info())
                ->onlyOnDetail()
                ->warning('This Transaction was already Completed')
                ->canSee(function ($request) {
                    $transaction = \App\Models\Transaction::find($request->resourceId);
                    if (!$transaction) {
                        return;
                    }
                    return $transaction->status == 'completed';
                }),
            (new Info())
                ->onlyOnDetail()
                ->danger('This Transaction was cancelled')
                ->canSee(function ($request) {
                    $transaction = \App\Models\Transaction::find($request->resourceId);
                    if (!$transaction) {
                        return;
                    }
                    return $transaction->status == 'cancelled';
                }),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [
            CompletedTransaction::make(),
            PendingTransaction::make(),
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            MarkAsComplete::make()
                ->onlyOnDetail(),
            new DownloadExcel(),
        ];
    }
}
