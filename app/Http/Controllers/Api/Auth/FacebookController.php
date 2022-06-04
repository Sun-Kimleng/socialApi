<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Http\Resources\UserResource;
use Exception;
use Illuminate\Support\Facades\Auth;

class FacebookController extends Controller
{
    
    public function loginUrl()
    {
        return response()->json([
            'url' => Socialite::driver('facebook')->stateless()->redirect()->getTargetUrl(),
        ]);

    }

    public function loginCallback()
    {
        

            try {
        
                $user = Socialite::driver('facebook')->stateless()->user();
                $isUser = User::where('fb_id', $user->id)->first();
               
         
                if($isUser){
                    Auth::login($isUser);
                    $token= $isUser->createToken($user->token)->plainTextToken;
                    return response()->json(['facebook_user'=>$isUser, 'token'=>$token]);
                }else{
                    $createUser = User::create([
                        'name' => $user->name,
                        'email' => $user->email,
                        'fb_id' => $user->id,
                        'password' => encrypt('admin@123')
                    ]);
                    
                    $token= $createUser->createToken($user->token)->plainTextToken;

                    return response()->json(['facebook_user'=>$user, 'token'=>$token, 'status'=>200]);
                }
        
            } catch (Exception $exception) {
                dd($exception->getMessage());
            }

    
    }
    
}
