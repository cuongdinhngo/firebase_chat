<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Http\Controllers\Room\RoomService;
use App\Http\Controllers\User\UserRepository;
use App\Http\Controllers\Message\MessageService;
use App\Events\PrivateMessage;

class RoomController extends Controller
{
	public $roomService;

	public function __construct(RoomService $roomService)
	{
		$this->roomService = $roomService;
	}

    /**
     * Enter the room
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function enterRoom(Request $request)
    {
        $sender = Auth::user();
    	$messages = app(MessageService::class)->getMessagesByConditions($sender->id, $request->receiver_id);
        $receiver = app(UserRepository::class)->findUserById($request->receiver_id);
        $group = [
            $sender->id => $sender,
            $receiver->id => $receiver
        ];
    	return view('room', compact(['messages', 'receiver', 'sender', 'group']));
    }
}
