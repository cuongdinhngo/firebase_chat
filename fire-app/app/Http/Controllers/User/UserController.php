<?php

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Controllers\User\UserService;
use App\Http\Controllers\Friend\FriendService;
use App\Http\Controllers\Room\RoomService;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    public $userService;
    protected $userRepo;
    protected $node = 'users';
    protected $messaging;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        parent::__construct($this->node);
        $this->userRepo = $this->repository;
        // $this->messaging = $messaging;
    }

    /**
     * Get current user login
     *
     * @return [type] [description]
     */
    public function getCurrentUserLogin()
    {
        return Auth::user();
    }

    /**
     * Get user
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getUser(Request $request)
    {
        $user = $this->userService->findUserById($request->id);
        $friend = app(FriendService::class)->getFriendStatus(Auth::user()->id, $request->id)->first();
        return view("user.show", compact(['user', 'friend']));
    }

    /**
     * User join room
     *
     * @param  Request     $request     [description]
     * @param  RoomService $roomService [description]
     * @return [type]                   [description]
     */
    public function connect(Request $request)
    {
        try {
            \DB::beginTransaction();
            app(FriendService::class)->beFriend($request->id);
            \DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            \DB::rollback();
            report($e);
        }
    }

    /**
     * List notifications
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function listNotifications(Request $request)
    {
        $userNotifications = Auth::user()->notifications;
        return view("notification", compact("userNotifications"));
    }

    public function saveToken(Request $request)
    {
        auth()->user()->update(['device_token'=>$request->token]);
        return response()->json(['token saved successfully.']);
    }
}
