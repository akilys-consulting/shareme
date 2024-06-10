<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

class GoogleController extends Controller
{
    //
    public function redirectToGoogle(){
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback(){
        $user = Socialite::driver('google')->stateless()->user();
        $findUser = User::where('email',$user->getEMail())->first;

        if ($findUser){
            Auth::login($findUser);
        
        } else {
            $newUser = User::create([
                'name'=> $user->getName(),
                'email'=> $user->getEmail(),
                'google_id'=>$user->getId(),
                'password'=>encrypt(Str::random(16))
            ]);

            Auth::login($newUser);
        }

        return response()->json(['token'=>$user->token]);
    }
}
