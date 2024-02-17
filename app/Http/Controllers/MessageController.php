<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class MessageController extends Controller
{

    public function create(): Renderable
    {
        return view('messages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // encrypting the message body takes place in the Message model (casts)
        $attributes = $request->validate([
            'body' => ['required', 'string']
        ]);

        // bcrypt hashing takes place in the Message model (setPasswordAttribute)
        $password = Str::random(12);
        $attributes['password'] = $password;

        $token = Str::random(32);
        $attributes['token'] = $token;

        Message::create($attributes);

        $url = URL::temporarySignedRoute(
            'messages.show',
            now()->addMinutes(30),
            ['token' => $token]
        );

        return redirect()->back()->with('messageStored', [
            'url' => $url,
            'password' => $password
        ]);
    }

    public function show(Request $request, string $token): Renderable
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        return view('messages.show', [
            'authenticated' => false,
            'token' => $token
        ]);
    }

    public function authenticate(Request $request, string $token): Renderable|RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string']
        ]);

        $message = Message::where('token', $token)->firstOrFail();

        if (Hash::check($request->password, $message->password)) {
            return view('messages.show', [
                'authenticated' => true,
                'token' => $message->token,
                // decrypting the message body takes place in the Message model (casts)
                'body' => $message->body
            ]);
        } else {
            return back()->with('error', 'Wachtwoord onjuist.');
        }
    }

}
