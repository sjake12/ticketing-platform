<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Socialite;
use Laravel\Socialite\Two\GoogleProvider;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        /** @var GoogleProvider $googleDriver */
        $googleDriver = Socialite::driver('google');

        try {
            $googleUser = $googleDriver->stateless()->user();

            if (empty($googleUser->getEmail())) {
                return redirect()->route('login')
                    ->withErrors('No email returned from Google account.');

            }

            $user = User::where('google_id', $googleUser->getId())
                ->where('email', $googleUser->getEmail())
                ->first();

            if (! $user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'provider' => 'google',
                    'avatar' => $googleUser->getAvatar(),
                    'password' => bcrypt(Str::random(24)),
                    'status' => 'Active',
                    'join_date' => now(),
                ]);
            } else {
                if (! $user->getGoogleId()) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'provider' => 'google',
                    ]);
                }
            }

            $user->update([
                'last_login' => now(),
            ]);

            Auth::login($user);

            return redirect()->route('home');
        } catch (\Exception $e) {
            \Log::error('Google OAuth Error', [
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('login')
                ->withErrors('Authentication failed. Please try again.');
        }
    }
}
