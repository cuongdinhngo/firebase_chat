<?php

namespace App\Http\Controllers\Friend;

use App\Models\Friend;

class FriendRepository
{
    public function insert(array $data)
    {
        return Friend::insert($data);
    }

    public function get(array $where, array $select = ['*'])
    {
        return Friend::select($select)->where($where)->get();
    }

    public function friends($currentUserId = null, $select = ['*'])
    {
        $first = Friend::select($select)
                    ->join('users', 'friends.answer_id', '=', 'users.id')
                    ->where([
                        ['requestor_id', '=', $currentUserId],
                        ['answer_id', '!=', $currentUserId],
                        ['status', '=', 1],
                    ]);

        $friends = Friend::select($select)
                    ->join('users', 'friends.requestor_id', '=', 'users.id')
                    ->where([
                        ['requestor_id', '!=', $currentUserId],
                        ['answer_id', '=', $currentUserId],
                        ['status', '=', 1],
                    ])
                    ->union($first)
                    ->get();

        return $friends;
    }
}
