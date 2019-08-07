<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

/**
 * Class SocialController
 * @package App\Http\Controllers\Auth
 */
class SocialController extends Controller
{
    const PROVIDERS_MAP = [
        'vkontakte' => 'vk',
    ];

    /**
     * @param string $provider
     * @return mixed
     */
    public function redirect(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * @param string $provider
     * @return RedirectResponse
     */
    public function callback(string $provider)
    {
        $getInfo = Socialite::driver($provider)->user();
        $user = Player::where(self::PROVIDERS_MAP[$provider], $getInfo->nickname)->first();

        if ($user) {
            auth()->login($user);
        }

        return redirect()->route('home');
    }

    /**
     * @return RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
