<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

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
        try {
            $validated = $request->validate([
                // User Credentials
                'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'role' => ['required', 'string', 'in:Student,Non-Student,Employee'],
                
                // Basic Information
                'salutation' => ['required', 'string', 'max:10'],
                'first_name' => ['required', 'string', 'max:255'],
                'middle_name' => ['nullable', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'gender' => ['required', 'string', 'in:Male,Female,Other'],
                'date_of_birth' => ['required', 'date', 'before:today'],
                'nationality' => ['required', 'string', 'max:255'],
                'mobile_no' => ['nullable', 'string', 'max:20'],
                'telephone_no' => ['nullable', 'string', 'max:20'],
                
                // Address Information
                'street' => ['nullable', 'string', 'max:255'],
                'city' => ['nullable', 'string', 'max:255'],
                'province' => ['required', 'string', 'max:255'],
                'country' => ['required', 'string', 'max:255'],
                'zipcode' => ['nullable', 'string', 'max:20'],
                
                // Password and Agreement
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'privacy_agreement' => ['required', 'accepted'],
            ]);

            Log::info('Validation passed', ['data' => $validated]);

            $user = User::create([
                // User Credentials
                'username' => $validated['username'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                
                // Basic Information
                'salutation' => $validated['salutation'],
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'],
                'nationality' => $validated['nationality'],
                'mobile_no' => $validated['mobile_no'],
                'telephone_no' => $validated['telephone_no'],
                
                // Address Information
                'street' => $validated['street'],
                'city' => $validated['city'],
                'province' => $validated['province'],
                'country' => $validated['country'],
                'zipcode' => $validated['zipcode'],
                
                // Password and Agreement
                'password' => Hash::make($validated['password']),
                'privacy_agreement' => $validated['privacy_agreement'],
            ]);
       

            Log::info('User created', ['user' => $user->toArray()]);

            event(new Registered($user));
            Auth::login($user);

            return redirect(route('dashboard', absolute: false));
        } catch (\Exception $e) {
            Log::error('Registration failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
