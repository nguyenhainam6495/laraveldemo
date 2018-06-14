<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Socialite;

class SocialAuthController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();   
    }   

    public function handleProviderCallback()
    {
        return Socialite::driver('google')->stateless()->user();
    
    }
}
