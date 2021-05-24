<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
    
    public function createFirebaseUser(array $data)
    {
        $userProperties = [
            'displayName' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ];

        return $this->auth->createUser($userProperties);
    }

    public function register(Request $request)
    {
        $data = $request->all();

        $this->validator($data)->validate();

        $userFirebase = $this->createFirebaseUser($data);

        $data['password'] = Hash::make($request->password);

        $data = array_merge($data, ['firebase_uid' => $userFirebase->uid]);
        event(new Registered($user = User::create($data)));

        //Send Email verification
        // $this->auth->sendEmailVerificationLink($request->email);

        return redirect()->route('login');
    }
}
