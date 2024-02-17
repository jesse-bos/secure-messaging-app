<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tests\TestCase;

class SecureMessageTest extends TestCase
{
    public function testMessagePasswordHashing()
    {
        // Arrange
        $password = Str::random(12);

        // Act
        $message = Message::factory()->create([
            'password' => $password
        ]);

        // Assert
        $this->assertNotEquals($password, $message->password);
        $this->assertTrue(Hash::check($password, $message->password));
    }

    public function testMessageTemporarySignedUrlExpires()
    {
        // Arrange
        $expirationTime = now()->addMinutes(30);

        // Act
        $message = Message::create([
            'body' => 'Body',
            'password' => Str::random(12),
            'token' => Str::random(32)
        ]);

        // Generate a signed URL with a 1-minute expiration time
        $url = URL::temporarySignedRoute(
            'messages.show',
            $expirationTime,
            ['token' => $message->token]
        );

        // Assert

        // Access the URL immediately, should return 200
        $response = $this->get($url);
        $response->assertStatus(200);

        $this->travel(40)->minutes();

        // Access the expired URL, should return 401
        $response = $this->get($url);
        $response->assertStatus(401);

        $this->travelBack();

        // Access the URL immediately, should return 200
        $response = $this->get($url);
        $response->assertStatus(200);
    }

    public function testAuthenticateMessageWithCorrectPassword()
    {
        // Arrange
        $password = 'correct_password';
        $token = 'test_token';

        //Act
        $message = Message::create([
            'body' => 'Test message body',
            'password' => $password, // Hash the password
            'token' => $token,
        ]);

        $response = $this->post('/messages/' . $token . '/authenticate', [
            'password' => $password,
        ]);

        // Assert
        $response->assertStatus(200)
            ->assertViewIs('messages.show')
            ->assertViewHas('authenticated', true)
            ->assertViewHas('token', $token)
            ->assertViewHas('body', $message->body);
    }

    public function testAuthenticateMessageWithWrongPassword()
    {
        // Arrange
        $password = 'correct_password';
        $token = 'test_token';

        // Act
        $message = Message::create([
            'body' => 'Test message body',
            'password' => $password, // Hash the password
            'token' => $token,
        ]);

        // Simulate a request with the correct password
        $response = $this->post('/messages/' . $token . '/authenticate', [
            'password' => 'wrong_password',
        ]);

        // Assert
        $response->assertRedirect()
            ->assertSessionHas('error', 'Wachtwoord onjuist.');
    }
}
