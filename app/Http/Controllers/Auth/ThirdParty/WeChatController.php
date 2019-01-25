<?php

namespace App\Http\Controllers\Auth\ThirdParty;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Overtrue\LaravelSocialite\Socialite;

class WeChatController extends Controller
{
    public function wechat(){
        return Socialite::driver('wechat')->redirect();
    }

    public function callback(){
        // get what ever wechat gave
        $user = Socialite::driver('wechat')->user();

        // check if already have an account
        $check = User::where('uid', $user->id)->where('provider', 'wechat')->first();
        if ($check) {
            Auth::login($check, true);
            return redirect('/');
        } else {
            // todo: Redirect to register view with callback info
            return redirect(route('register'), $user);
        }
    }
}
