<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Provider;
use acidjazz\Humble\Models\Session;

use Illuminate\Http\Request;
use App\Notifications\UserAttempt;
use Illuminate\Support\Facades\Auth;

use Socialite;

class AuthController extends Controller
{

    /**
     * Return all existing user sessions
     *
     * @return Illuminate\Http\Response
     */
    public function sessions() {
        return $this->render(Auth::user()->sessions);
    }

    /**
     * Supply the appropiate callback URL
     *
     * @return String
     */
    public function redirect(Request $request, $provider)
    {
        if (!in_array($provider, Provider::$allowed)) {
            return $this->error('auth.provider.allowed', 'Auth Provider is not allowed');
        }
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Process and result the oAUth providers callback
     *
     * @response Illuminate\Http\Response
     */
    public function callback(Request $request, String $provider)
    {
        if (!in_array($provider, Provider::$allowed)) {
            return $this->error('auth.provider.allowed', 'Auth Provider is not allowed');
        }

        $oaUser = Socialite::driver($provider)->stateless()->user();

        $user = User::where('email', $oaUser->email)->first();

        if ($user == null) {
            $user = new User([
                'name' => $oaUser->name,
                'email' => $oaUser->email,
                'avatar' => $oaUser->avatar_original ?? $oaUser->avatar,
            ]);
            if (!$user->save()) {
                return $this->error('auth.user_save', 'Error saving user');
            }
        }

        if ($user == null || !in_array($provider, $user->providers->pluck('name')->toArray())) {
            $providerModel = new Provider([
                'user_id' => $user->id,
                'name' => $provider,
                'avatar' => $oaUser->avatar_original ?? $oaUser->avatar,
                'payload' => $request->get('state'),
            ]);
            if (!$providerModel->save()) {
                return $this->error('auth.provider_save', 'Error saving provider');
            }

            if ($user->avatar == null) {
                $user->avatar = $providerModel->avatar;
                $user->save();
            }
        }

        Auth::login($user, $provider);

        return response(
            view('complete', [
                'json' => json_encode([
                'provider' => $provider,
                'token' => Auth::token(),
                'user' => Auth::user()->append('stats'),
                'to' => Auth::session()->to,
            ])
        ]))->cookie('token', Auth::token(), 0, '', config('app.domain'));
    }

    /**
     * Passwordless login attempt
     *
     * @param Illuminate\Http\Request
     * @return Illuminate\Http\Response
     */
    public function attempt(Request $request)
    {
        $this->option('email', 'required|email');
        $this->option('to', 'string');
        $this->verify();

        if (!$user = User::where('email', $request->email)->first()) {
            return $this->render(['cookie' => Session::hash()]);
        }

        $attempt = Auth::attempt($user);
        $user->notify(new UserAttempt($attempt));

        return $this->render(['cookie' => $attempt->cookie]);
    }

    /**
     * Process a link from an Login e-mail
     *
     * @param Illuminate\Http\Request
     * @return Illuminate\Http|Response
     */
    public function login(Request $request)
    {
        $this->option('token', 'required|alpha_num|size:64');
        $this->option('cookie', 'required|alpha_num|size:64');

        if (!$this->verify()) {
        return $this->error();
        }

        if (Auth::user() != null) {
        return $this->error('auth.already');
        }

        if (!$login = Auth::verify($request->token, $request->cookie)) {
        return $this->error('auth.invalid');
        }

        return $this->render([
            'token' => Auth::token(),
            'user' => Auth::user(),
            'to' => Auth::session()->to,
        ])->cookie('token', Auth::token(), 0, '', config('app.domain'));
    }

    /**
     * Provide current login information
     *
     * @param Illuminate\Http\Request
     * @return Illuminate\Http\Response
     */
    public function me(Request $request)
    {
        return $this->render(Auth::user()->append('stats'));
    }

    /**
     * Log out of the current session
     *
     * @param Illuminate\Http\Request
     * @return Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        return $this->render(Auth::logout())->cookie('token', false, 0, '', config('app.domain'));
    }
}
