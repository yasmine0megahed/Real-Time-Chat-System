<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\message\StoreMessageRequest;
use App\Models\Message;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class MessageController extends Controller
{

    public function getMessages($user_id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user->id != $user_id) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            $messages = Message::where('receiver_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'data' => $messages->items(), // Messages array==>paginate
                'meta' => [
                    'current_page' => $messages->currentPage(),
                    'total' => $messages->total(),
                ],
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'something went wrong', 'error' => $e->getMessage()], 500);
        }
    }

    public function sendMessage(StoreMessageRequest $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            // save msg to db
            $message = Message::create([
                'receiver_id' => $request->receiver_id,
                'sender_id' => $user->id,
                'message' => $request->message
            ]);
            // fire the event
            if (cache()->get('user-is-online-' . $user->id)) {
                broadcast(new MessageSent($message))->toOthers();
            }

            return response()->json(['message' => 'message sent successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'something went wrong', 'error' => $e->getMessage()], 500);
        }
    }


    public function markMessageAsRead($message_id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            // find 
            $message = Message::find($message_id);
            if ($message->receiver_id == $user->id) {
                $message->status = 'read';
                $message->save();
                return response()->json(['message' => 'message marked as read successfully'], 200);
            }
            return response()->json(['message' => 'Unauthorized'], 401);
        } catch (Exception $e) {
            return response()->json(['message' => 'something went wrong', 'error' => $e->getMessage()], 500);
        }
    }

    public function setOnline()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            cache()->set('user-is-online-' . $user->id, true, now()->addMinutes(5));
            $messages = Message::where('receiver_id', $user->id)
                ->where('status', 'delivered')
                ->get();

            // If there are any delivered but unread messages, broadcast them
            foreach ($messages as $message) {
                // fire the event
                broadcast(new MessageSent($message));  
            }

            return response()->json(['message' => 'User is online and unread messages delivered'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'something went wrong', 'error' => $e->getMessage()], 500);
        }
    }
    public function setOffline()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            cache()->forget('user-is-online-' . $user->id);
            return response()->json(['message' => 'is Offline'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'something went wrong', 'error' => $e->getMessage()], 500);
        }
    }
}
