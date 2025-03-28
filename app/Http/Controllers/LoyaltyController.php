<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyPoint;
use App\Models\User;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    public function index()
    {
        $loyaltyPoints = LoyaltyPoint::with('user')->paginate(10);
        return view('loyalty.index', compact('loyaltyPoints'));
    }

    public function show(User $user)
    {
        $loyalty = LoyaltyPoint::where('user_id', $user->id)->firstOrFail();
        return view('loyalty.show', compact('loyalty'));
    }

    public function addPoints(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0'
        ]);

        $loyalty = LoyaltyPoint::firstOrCreate(
            ['user_id' => $user->id],
            ['points' => 0, 'total_spent' => 0, 'tier' => 'bronze']
        );

        $loyalty->addPoints($request->amount);

        return redirect()->back()->with('success', 'Points added successfully');
    }
}
