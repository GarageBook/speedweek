<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        if ($request->filled('event')) {
            $event = Event::where('slug', $request->query('event'))->first();

            if ($event) {
                $request->session()->put('url.intended', route('events.register', $event, absolute: false));
            }
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'event_slug' => ['nullable', 'exists:events,slug'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        Log::info('auth.register.created', [
            'user_id' => $user->id,
            'has_event_slug' => $request->filled('event_slug'),
            'is_admin' => (bool) $user->is_admin,
        ]);

        if ($request->filled('event_slug')) {
            $event = Event::where('slug', $request->input('event_slug'))->first();

            if ($event) {
                return redirect()->route('events.register', $event);
            }
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
