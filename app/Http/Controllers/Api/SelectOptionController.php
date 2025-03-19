<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Models\SelectOption;

class SelectOptionController extends ApiBaseController
{
    protected $model = SelectOption::class;

    public function getGroupOptions($group)
    {
        $options = cache()->remember('select_options_' . $group, 60 * 60 * 24, function () use ($group) {
            return SelectOption::where('group', $group)->select('id' ,'key' , 'value')->get();
        });
        
        return response()->json([
            'data' => $options,
        ]);
    }
}
