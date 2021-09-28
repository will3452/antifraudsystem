<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;

class MarkAsComplete extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if (!Hash::check($fields['password'], auth()->user()->password)) {
            return Action::danger('Please provide correct password!');
        }
        foreach ($models as $model) {
            if($model->status != 'pending') continue;
            $model->update([
                'status' => 'completed',
                'completed_at' => now(),
                'completed_by' => auth()->id()
            ]);
        }
        return Action::redirect(url()->previous());
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Text::make('Password')
                ->placeholder('Please Enter your Password Account!')
                ->rules('required')
        ];
    }
}
