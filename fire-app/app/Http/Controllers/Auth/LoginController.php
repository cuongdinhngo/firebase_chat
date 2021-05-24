<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\User\UserService;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = "/messages/public";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest')->except('logout');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function login(Request $request)
    {
        \DB::enableQueryLog();
        try {
            $result = $this->auth->signInWithEmailAndPassword($request->email, $request->password);

            if ($idToken = $result->idToken() && $uid = $result->firebaseUserId()) {
                $request->request->add(['firebase_uid' => $uid]);
                $this->validateLogin($request);

                if ($this->attemptLogin($request)) {
                    $customToken = $this->auth->createCustomToken($uid)->toString();
                    logger([
                        'device_token' => $request->device_token,
                        'id_token' => $idToken,
                        'custom_token' => $customToken,
                    ]);
                    auth()->user()->update([
                        'device_token' => $request->device_token,
                        'id_token' => $idToken ,
                        'custom_token' => $customToken,
                    ]);
                    logger(\DB::getQueryLog());
                    return $this->sendLoginResponse($request);
                }
            }
        } catch (\Exception $e) {
            logger($e);
            return redirect('/login');
        }
    }

    public function username()
    {
        return 'firebase_uid';
    }
}
