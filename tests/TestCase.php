<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function createLoggedUser()
    {
        $user = User::factory()->create(['is_admin' => 0, 'locale' => 'en']);

        $this->followingRedirects()->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        return $user;
    }

    public function createLoggedAdminUser()
    {
        $user = User::factory()->create(['is_admin' => 1]);

        $this->followingRedirects()->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        return $user;
    }
}
