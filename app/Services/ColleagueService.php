<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ColleagueService
{
    public function getColleagues(): array
    {
        $response = Http::get('https://pastebin.com/raw/uDzdKzGG');
        return $response->json();
    }
}
