<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Http\Controllers\User\UserRepository;
use Auth;
use App\Models\User;

class ListUsersComposer
{
    protected $userRepository;
    protected $user;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        // Dependencies automatically resolved by service container...
        $this->userRepository = $userRepository;
        $this->user = Auth::user();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('data', [
            'listOfUsers' => $this->userRepository->listAllUsers(true),
            // 'userFriends' => $this->user->friends(),
        ]);
    }
}