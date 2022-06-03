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
        
        $facebookUser = Socialite::driver('google')->stateless()->user();
        $user = null;

        DB::transaction(function () use ($facebookUser, &$user) {
            $socialAccount = SocialAccount::firstOrNew(
                ['social_id' => $facebookUser->getId(), 'social_provider' => 'google'],
                ['social_name' => $facebookUser->getName()]
            );

            if (!($user = $socialAccount->user)) {
                $user = User::create([
                    'email' => $facebookUser->getEmail(),
                    'name' => $facebookUser->getName(),
                ]);
                $socialAccount->fill(['user_id' => $user->id])->save();
            }
        });

        return response()->json([
            'user' => new UserResource($user),
            'google_user' => $facebookUser,
        ]);
    
    }
    
}
