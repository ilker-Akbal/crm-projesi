<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/
Broadcast::channel('example.{id}', fn ($user, $id) => (int) $user->id === (int) $id);
