<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    public function warehouseIndex()
    {

        $notifications = Notification::where('user_id', Auth::user()->warehouse->id)->latest()
            ->get();;

        return view('warehouse.notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);


        // تحديث read_at
        $notification->update(['read_at' => now()]);

        // التأكد من وجود المسار الصحيح
        $redirectUrl = route('warehouse.orders.show', $notification->order_id);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الإشعار بنجاح',
            'redirect' => $redirectUrl,
        ], 200);
    }
}
