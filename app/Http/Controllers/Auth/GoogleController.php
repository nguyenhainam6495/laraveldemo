<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Socialite;
use Auth;
use App\User;

class GoogleController extends Controller
{
    public function redirectToProvider()
    {
    	return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
    	$user = Socialite::driver('google')->user();

        $data = [
            'email' => $user->email,
            'password' => $user->token,
            'google_id' => $user->id,
            'name' => $user->name,      
        ];

        $user = User::where('email', $data['email'])->first();

        if (isset($user)) {
            Auth::login($user);
        }else{
            User::create($data);
            Auth::login($user);
        }
        return redirect()->route('trangchu');
    
    }
}
