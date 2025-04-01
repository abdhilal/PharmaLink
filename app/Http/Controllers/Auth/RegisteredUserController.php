<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseCash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:pharmacy,warehouse']
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        if ($request->role == 'warehouse') {
            $user->assignRole($request->role);
            $warehouse = Warehouse::create([
                'user_id' => $user->id,

            ]);
            
            WarehouseCash::create(['warehouse_id' => $warehouse->id]);
        }
        event(new Registered($user));

        Auth::login($user);



        if (Auth::user()->role == 'warehouse') {

            return redirect(route('warehouse.settings.cities', absolute: false));
        } else {

            return redirect(route('pharmacy.settings.cities', absolute: false));
        }
    }
}
