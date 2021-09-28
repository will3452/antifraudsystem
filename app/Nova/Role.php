<?php

namespace App\Nova;

use Coreproc\NovaPermissionsField\NovaPermissionsField;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Role extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Role::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make(__('Name'), 'name')
                ->readonly(function () {
                    /** @phpstan-ignore-next-line */
                    if ($this->name === \App\Models\Role::ROLE_SUPER_ADMIN) {
                        return true;
                    }
                    return false;
                })
                ->sortable()
                ->rules(['required', 'string', 'max:125'])
                ->creationRules('unique:' . config('permission.table_names.roles'))
                ->updateRules('unique:' . config('permission.table_names.roles') . ',name,{{resourceId}}'),
            NovaPermissionsField::make(__('Permissions'), 'prepared_permissions')
                ->canSee(function () {
                    /** @phpstan-ignore-next-line */
                    if ($this->name === \App\Models\Role::ROLE_SUPER_ADMIN) {
                        return false;
                    }
                    return true;
                })
                ->hideFromIndex()
                ->withGroups()
                ->options(\App\Models\Permission::all()->map(function ($permission, $key) {
                    return [
                        'group' => __(ucfirst($permission->group)),
                        'option' => $permission->name,
                        'label' => __($permission->name),
                    ];
                })->groupBy('group')->toArray()),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
