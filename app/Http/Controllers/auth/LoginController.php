<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\ActivationCompanyUser;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $activationCompanyUsers = ActivationCompanyUser::with('company')
                ->with('roles')
                ->active()
                ->byUser(auth()->id())
                ->get();


            return response()->json($activationCompanyUsers);
        }

        return response()->json([
            'email' => 'The provided credentials do not match our records.',
        ], 400);
    }


    public function logout(Request $request)
    {
        Cache::flush('usuario' . auth()->user()->id);
        Cache::flush('permissions' . auth()->user()->id);

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->regenerate();

        return response()->json([], 204);
    }
}
