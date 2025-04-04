<?php

use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('chat.{UserId}', function ($user, $UserId) {
    return (int) $user->id === (int) $UserId;
});
