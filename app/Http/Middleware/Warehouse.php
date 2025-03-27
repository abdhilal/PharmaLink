<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Warehouse
{

    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->role !== 'warehouse') {
            // إرجاع المستخدم إلى الصفحة السابقة
            return redirect()->back()->with('error', 'غير مصرح لك بالوصول.');
        }
        return $next($request);
    }
}
