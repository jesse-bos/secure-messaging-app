<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        return redirect('/');
    }

}
