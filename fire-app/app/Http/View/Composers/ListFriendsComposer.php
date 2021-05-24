<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Http\Controllers\UserStatus\UserStatusController;

class ListFriendsComposer
{

    /**
     * Create a new profile composer.
     *
     * @return void
     */
    public function __construct()
    {
        // Dependencies automatically resolved by service container...
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('friends', app(UserStatusController::class)->index());
    }
}