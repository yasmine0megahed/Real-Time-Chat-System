<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['message', 'sender_id', 'receiver_id', 'status'];
    
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    public function scopeUnread($query)
    {
        return $query->where('status', 'delivered');
    }
    public function isOnline(){
        return cache()->has('user-is-online-' . $this->id);
    }
}
