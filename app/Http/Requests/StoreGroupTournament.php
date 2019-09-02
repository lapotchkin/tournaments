<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class StoreGroupTournament
 * @package App\Http\Requests
 */
class StoreGroupTournament extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'platform_id'      => 'required|string|exists:platform,id',
            'app_id'           => 'required|string|exists:app,id',
            'title'            => 'required|string',
            'playoff_rounds'   => 'int|min:1|max:4',
            'min_players'      => 'required|int|in:3,6',
            'thirdPlaceSeries' => 'int|in:0,1',
        ];
    }
}
