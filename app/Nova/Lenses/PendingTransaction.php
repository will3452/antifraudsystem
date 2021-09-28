<?php

namespace App\Nova\Lenses;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Panel;
use Sixlive\TextCopy\TextCopy;

class PendingTransaction extends Lens
{
    /**
     * Get the query builder / paginator for the lens.
     *
     * @param \Laravel\Nova\Http\Requests\LensRequest $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query->where('status', 'pending')
        ));
    }

    /**
     * Get the fields available to the lens.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            TextCopy::make('Reference Number')
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
        ];
    }

    public function senderFields(): array
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

    public function receiverFields(): array
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
                ->rules(['required']),

            Text::make('Receiver Mobile')
                ->hideFromIndex()
                ->rules(['required', 'email']),
        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'pending-transaction';
    }
}
