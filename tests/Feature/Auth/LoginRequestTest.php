<?php

namespace Tests\Feature\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class LoginRequestTest extends TestCase
{
    public function test_authenticate_fails_and_hits_rate_limiter()
    {
        // Create a mock request with invalid credentials
        $request = LoginRequest::create('/login', 'POST', [
            'email' => 'invalid@example.com',
            'password' => 'invalidpassword',
        ]);

        // Mock RateLimiter::tooManyAttempts to return false to allow the attempt
        RateLimiter::shouldReceive('tooManyAttempts')
            ->once()
            ->andReturn(false);

        // Mock Auth::attempt to always return false
        Auth::shouldReceive('attempt')
            ->once()
            ->with(['email' => 'invalid@example.com', 'password' => 'invalidpassword'], false)
            ->andReturn(false);

        // Mock RateLimiter::hit to be called after failed authentication
        RateLimiter::shouldReceive('hit')
            ->once()
            ->with($request->throttleKey());

        // Mock RateLimiter::clear to ensure it won't be called
        RateLimiter::shouldReceive('clear')
            ->never();

        // Expect a ValidationException to be thrown with the appropriate message
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(trans('auth.failed'));

        // Call the authenticate method
        $request->authenticate();
    }

    public function test_authenticate_passes_and_clears_rate_limiter()
    {
        // Create a mock request with valid credentials
        $request = LoginRequest::create('/login', 'POST', [
            'email' => 'valid@example.com',
            'password' => 'validpassword',
        ]);

        // Mock RateLimiter::tooManyAttempts to return false to allow the attempt
        RateLimiter::shouldReceive('tooManyAttempts')
            ->once()
            ->andReturn(false);

        // Mock Auth::attempt to return true
        Auth::shouldReceive('attempt')
            ->once()
            ->with(['email' => 'valid@example.com', 'password' => 'validpassword'], false)
            ->andReturn(true);

        // Mock RateLimiter::clear to be called after successful authentication
        RateLimiter::shouldReceive('clear')
            ->once()
            ->with($request->throttleKey());

        // Call the authenticate method
        $request->authenticate();
    }

    public function test_ensure_is_not_rate_limited_when_limit_not_exceeded()
    {
        // Create a mock request
        $request = LoginRequest::create('/login', 'POST', [
            'email' => 'user@example.com',
        ]);

        // Mock RateLimiter to return false for tooManyAttempts
        RateLimiter::shouldReceive('tooManyAttempts')
            ->once()
            ->with($request->throttleKey(), 5)
            ->andReturn(false);

        // Call the ensureIsNotRateLimited method
        // No exception should be thrown
        $request->ensureIsNotRateLimited();
    }

    public function test_ensure_is_not_rate_limited_when_limit_exceeded()
    {
        // Create a mock request
        $request = LoginRequest::create('/login', 'POST', [
            'email' => 'user@example.com',
        ]);

        // Mock RateLimiter to return true for tooManyAttempts
        RateLimiter::shouldReceive('tooManyAttempts')
            ->once()
            ->with($request->throttleKey(), 5)
            ->andReturn(true);

        // Mock RateLimiter to return a specific number of seconds available in
        RateLimiter::shouldReceive('availableIn')
            ->once()
            ->with($request->throttleKey())
            ->andReturn(120);

        // Make sure the Lockout event is fired
        Event::fake();

        // Expect a ValidationException to be thrown
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(trans('auth.throttle', ['seconds' => 120, 'minutes' => 2]));

        // Call the ensureIsNotRateLimited method
        $request->ensureIsNotRateLimited();

        // Assert that the Lockout event was dispatched
        Event::assertDispatched(Lockout::class);
    }
}
