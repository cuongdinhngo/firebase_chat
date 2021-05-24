<?php

namespace App\Http\Controllers\UserStatus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Friend\FriendService;

class UserStatusController extends Controller
{
    const NODE = 'status';
    public $userStatusRepo;

    public function __construct()
    {
        parent::__construct(self::NODE);
    }
    public function index()
    {
        $select = ['users.id', 'users.name', 'users.firebase_uid', 'users.device_token'];
        $friends = app(FriendService::class)->getFriendList(auth()->user()->id, $select);
        return $friends->toArray();
    }
}
