<?php

namespace Tests\Unit;
use Illuminate\Support\Str;
use App\Events\MessageSent;
use App\Models\User;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class MessageTest extends TestCase
{
    // use RefreshDatabase;

    public function test_register()
    {
        $randomName = 'user_' . Str::random(5);
        $randomEmail = $randomName . '@example.com';
    
        $receiverResponse = $this->postJson('api/register', [
            'name' => $randomName,
            'email' => $randomEmail,
            'password' => 'password',
        ]);
    
        $receiverResponse->assertStatus(201);
        Log::info('Random Receiver Registered:', $receiverResponse->json());
    
        $receiver = $receiverResponse->json('user');
        $this->assertEquals($randomEmail, $receiver['email']);
    }



    public function test_message_can_be_sent_and_broadcasted()
    {
        log::info('In sent MessageTest');
        $receiverRandomName = 'user_' . Str::random(5);
        $receiverRandomEmail = $receiverRandomName . '@example.com';
        $senderRandomName = 'user_' . Str::random(5);
        $senderRandomEmail = $senderRandomName . '@example.com';
        // Register receiver
        $receiverResponse = $this->postJson('api/register', [
            'name' => $receiverRandomName,
            'email' => $receiverRandomEmail,
            'password' => 'password',
        ]);
        $this->assertEquals(201, $receiverResponse->status());
        Log::info('Receiver Response:', $receiverResponse->json());
        $receiver = $receiverResponse->json('user');

        // Register sender
        $senderResponse = $this->postJson('api/register', [
            'name' => $senderRandomName,
            'email' => $senderRandomEmail,
            'password' => 'password',
        ]);
        $this->assertEquals(201, $senderResponse->status()); // Ensure successful registration
        Log::info('Sender Response:', $senderResponse->json());
        $sender = $senderResponse->json('user');

        // login for sender to get JWT token
        $loginResponse = $this->postJson('api/login', [
            'email' => $sender['email'],
            'password' => 'password',
        ]);
        // Ensure successful login
        $this->assertEquals(200, $loginResponse->status());
        $token = $loginResponse->json('access_token');

        // Prepare message data
        $messageData = [
            'receiver_id' => $receiver['id'],
            'message' => 'Hello, how are you?',
        ];

        // Send the message with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/chat/send', $messageData);

        // Assert the response
        $response->assertStatus(200);
        $response->assertJson(['message' => 'message sent successfully']);
    }
    public function test_get_message(){
        Log::info('In get MessageTest');

        $receiver = User::inRandomOrder()->first();
        $receiverRandomEmail = $receiver->email;
        // login for receiver to get JWT token
        $loginResponse = $this->postJson('api/login', [
            'email' =>$receiverRandomEmail ,
            'password' => 'password',
        ]);
        // Ensure successful login
        $this->assertEquals(200, $loginResponse->status());
        $token = $loginResponse->json('access_token');

        $user = User::where('email', $receiverRandomEmail)->first();
        // Now call the get messages endpoint
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get("/api/chat/messages/{$user->id}");
        $response->assertStatus(200);

    }
}
