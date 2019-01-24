<?php

namespace App\Http\Controllers\Auth\ThirdParty;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WeiChatController extends Controller
{
    public function wechat(){
        return Socialite::with('Weixin')->redirect();
    }

    public function wechatlogin(){
        $user = Socialite::driver('Weixin')->user();
//        dd($user);
        $check = User::where('uid', $user->id)->where('provider', 'qq_connect')->first();
        if (!$check) {
            $customer = User::create([
                'uid' => $user->id,
                'provider' => 'qq_connect',
                'name' => $user->nickname,
                'email' => 'qq_connect+' . $user->id . '@example.com',
                'password' => bcrypt(Str::random(60)),
                'avatar' => $user->avatar
            ]);
        } else {
            $customer = $check;
        }

        Auth::login($customer, true);
        return redirect('/');
    }
}
