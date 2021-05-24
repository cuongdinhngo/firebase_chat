<?php

namespace App\Http\Controllers\Friend;

use App\Http\Controllers\Friend\FriendRepository;
use App\Models\Friend;

class FriendService
{
    public function beFriend($exepectedFriend)
    {
        $data = [
            'requestor_id' => auth()->user()->id,
            'answer_id' => $exepectedFriend,
        ];
        return app(FriendRepository::class)->insert($data);
    }

    public function getFriendStatus($currentUser, $friendId)
    {
        return Friend::where([
                    ['requestor_id', '=', $currentUser],
                    ['answer_id', '=', $friendId],
                ])
                ->orWhere([
                    ['requestor_id', '=', $friendId],
                    ['answer_id', '=', $currentUser],
                ])
                ->get();
    }

    public function getFriendList($currentUserId = null, $select = ['*'])
    {
        if (is_null($currentUserId)) {
            $currentUserId = auth()->user()->id;
        }
        return app(FriendRepository::class)->friends($currentUserId, $select);
    }
}
