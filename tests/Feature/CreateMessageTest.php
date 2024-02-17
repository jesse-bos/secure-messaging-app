<?php
declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class CreateMessageTest extends TestCase
{
    public function testCreateMessageFormIsShown()
    {
        $response = $this->get(action('\App\Http\Controllers\MessageController@create'));

        $response->assertOk();
        $response->assertSee(config('app.name'));
        $response->assertSee('Plaats hier je bericht');
        $response->assertSee('Versleutel bericht');
    }
}
