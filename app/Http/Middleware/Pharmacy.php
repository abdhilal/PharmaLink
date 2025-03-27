<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Pharmacy
{

    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->role !== 'pharmacy') {
            // إرجاع المستخدم إلى الصفحة السابقة
            return redirect()->back()->with('error', 'غير مصرح لك بالوصول.');
        }
        return $next($request);
    }
}
