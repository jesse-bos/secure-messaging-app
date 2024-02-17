<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\MessageNotification;
use App\Models\Message;
use App\Services\ColleagueService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MessageController extends Controller
{

    public function create(): Renderable
    {
        $colleagueService = new ColleagueService();
        $colleagues = $colleagueService->getColleagues();

        return view('messages.create', [
            'colleagues' => $colleagues
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // encrypting the message body takes place in the Message model (casts)
        $attributes = $request->validate([
            'body' => ['required', 'string'],
            'mail_to' => ['sometimes', 'nullable', 'email']
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

        if ($mail_to = Arr::get($attributes, 'mail_to')) {
            Mail::to($mail_to)->send(new MessageNotification($password, $url));
        }

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

    public function destroy(string $token): RedirectResponse
    {
        $message = Message::where('token', $token)->firstOrFail();

        $message->delete();

        return redirect('/');
    }

}
