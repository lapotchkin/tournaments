<?php


namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StorePersonalTournament
 *
 * @package App\Http\Requests
 */
class StorePersonalTournament extends FormRequest
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
            'league_id'        => 'required|string|exists:league,id',
            'title'            => 'required|string',
            'playoff_rounds'   => 'int|min:1|max:4',
            'thirdPlaceSeries' => 'int|in:0,1',
            'vk_group_id'      => 'int',
            'startedAt'        => 'date',
            'playoff_limit'   => 'int|min:1',
        ];
    }
}
