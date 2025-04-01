<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});



Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    // التحقق من أن المستخدم المسجل هو نفسه صاحب القناة
    return (int) $user->warehouse->id === (int) $userId;
});
