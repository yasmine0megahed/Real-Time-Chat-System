<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = [
            ['sender_id' => 1, 'receiver_id' => 2, 'message' => 'Hello'],
            ['sender_id' => 2, 'receiver_id' => 1, 'message' => 'Hi'],
            ['sender_id' => 1, 'receiver_id' => 2, 'message' => 'How are you?'],
            ['sender_id' => 2, 'receiver_id' => 1, 'message' => 'I am fine, thanks'],
            ['sender_id' => 1, 'receiver_id' => 2, 'message' => 'Great!'],
            ['sender_id' => 2, 'receiver_id' => 1, 'message' => 'See you later'],
            ['sender_id' => 1, 'receiver_id' => 2, 'message' => 'Bye'],
            ['sender_id' => 2, 'receiver_id' => 1, 'message' => 'Goodbye'],
            ['sender_id' => 1, 'receiver_id' => 2, 'message' => 'See you again'],
            ['sender_id' => 2, 'receiver_id' => 1, 'message' => 'Until next time'],
            ['sender_id' => 1, 'receiver_id' => 2, 'message' => 'Thanks for chatting'],
            ['sender_id' => 2, 'receiver_id' => 1, 'message' => 'You are welcome'],
            ['sender_id' => 1, 'receiver_id' => 2, 'message' => 'Have a nice day'],
            ['sender_id' => 2, 'receiver_id' => 1, 'message' => 'Goodbye for now'],
            ['sender_id' => 1, 'receiver_id' => 2, 'message' => 'See you later'],
            ['sender_id' => 2, 'receiver_id' => 1, 'message' => 'Bye'],
            ['sender_id' => 1, 'receiver_id' => 2, 'message' => 'Goodbye'],

        ];
        foreach ($messages as $message) {
            Message::create($message);
        }
   
    }
}
