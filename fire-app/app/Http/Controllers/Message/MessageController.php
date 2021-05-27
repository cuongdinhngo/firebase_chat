<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Message\MessageService;
use Auth;
use App\Events\PublicMessage;
use App\Libraries\ClientUrl;

class MessageController extends Controller
{
    public $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = [];
        return view('chat', compact('messages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $message = $this->messageService->postMessage($request->sender_id, $request->receiver_id);
            $response = $this->sendNotification($request->device_token, $this->messageService->composeNotificationContent($message));
            return ['message' => $message, 'response' => $response];
        } catch (\Exception $e) {
            report($e);
        }
    }

    public function sendNotification($deviceToken, $message)
    {
        $client = new ClientUrl();
        $data = [
            "to" => $deviceToken,
            "notification" => [
                "title" => 'Message Notification',
                "body" => $message,  
            ]
        ];
        $headers = [
            'Authorization: key=' . env('FCM_SERVER_KEY'),
            'Content-Type: application/json',
        ];

        $rs = $client->url('https://fcm.googleapis.com/fcm/send')
            ->header($headers)
            ->returnTransfer(true)
            ->postFields(json_encode($data))
            ->exec()
            ->info()
            ;
        logger($rs->getInfo());
        return $rs;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
