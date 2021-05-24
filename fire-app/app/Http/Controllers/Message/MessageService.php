<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Message\MessageRepository;
use App\Models\Message;
use App\Models\User;

class MessageService
{
    /**
     * Get message in public room
     *
     * @return [type] [description]
     */
    public function getMessagesInPublicRoom()
    {
        return app(MessageRepository::class)->get();
    }

    /**
     * Post message in Public room
     *
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function postMessage($senderId, $receiverId)
    {
        $message = $this->prepareInsertMessageData($senderId, $receiverId);
        app(MessageRepository::class)->insert($message);
        return $message;
    }

    public function prepareInsertMessageData($senderId, $receiverId)
    {
        $message = new Message();
        $message->content = request()->get('message', '');
        $message->sender_id = $senderId;
        $message->receiver_id = $receiverId;
        return $message;
    }

    public function getMessagesByConditions($senderId, $receiverId)
    {
        return app(MessageRepository::class)->list($senderId, $receiverId);
    }
}
