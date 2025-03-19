<?php

namespace App\Http\Controllers\Api;

use Examyou\RestAPI\ApiResponse;
use App\Models\AppNotificationUser;
use App\Http\Controllers\ApiBaseController;

class AppNotificationUserController extends ApiBaseController
{
    protected $model = AppNotificationUser::class;

    public function getUnreadNotifications()
    {
        $user = user();

        $unreadNotifications = AppNotificationUser::where('user_id', $user->id)
            ->where('read_at', null)
            ->count();

        return response()->json([
            'data' => $unreadNotifications
        ]);
    }

    public function markAsRead($id)
    {
        $user = user();
        $success = false;

        $notification = AppNotificationUser::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if ($notification) {
            $success = true;
            $notification->read_at = now();
            $notification->save();
        }

        return ApiResponse::make('Success', [
            'success' => $success,
        ]);
    }

    public function markAllAsRead()
    {
        $user = user();
        $success = false;

        $notifications = AppNotificationUser::where('user_id', $user->id)
            ->where('read_at', null)
            ->get();

        if ($notifications) {
            $success = true;
            foreach ($notifications as $notification) {
                $notification->read_at = now();
                $notification->save();
            }
        }

        return ApiResponse::make('Success', [
            'success' => $success,
        ]);
    }
}
