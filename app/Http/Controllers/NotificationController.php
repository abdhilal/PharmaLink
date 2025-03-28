<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->latest()
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return back()->with('success', 'تم تحديد الإشعار كمقروء');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        return back()->with('success', 'تم تحديد جميع الإشعارات كمقروءة');
    }

    public function clear()
    {
        Auth::user()->notifications()->delete();

        return back()->with('success', 'تم حذف جميع الإشعارات');
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        
        // تحديث إعدادات الإشعارات
        $user->notification_settings = [
            'inventory' => $request->has('inventory'),
            'orders' => $request->has('orders'),
            'debts' => $request->has('debts'),
            'system' => $request->has('system'),
        ];
        
        $user->save();

        return back()->with('success', 'تم تحديث إعدادات الإشعارات');
    }
}
