<?php
// use Examyou\RestAPI\Facades\ApiRoute;
use App\Models\TeamChatUser;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('team-chat.{teamChatId}', function ($user, $teamChatId) {
    error_log('team-chat.{teamChatId} - User ID: ' . $user->id . ', Team Chat ID: ' . $teamChatId);
    $isAuthorized = TeamChatUser::where('team_chat_id', $teamChatId)
                                ->where('user_id', $user->id)
                                ->exists();
    error_log('Authorization Result: ' . ($isAuthorized ? 'Authorized' : 'Not Authorized'));
    return $isAuthorized;
});