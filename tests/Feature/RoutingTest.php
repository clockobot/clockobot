<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class RoutingTest extends TestCase
{
    use RefreshDatabase;

    #[Group('routing')]
    public function test_all_screens_can_be_rendered_for_authenticated_users(): void
    {
        // unprotected routes
        $response = $this->get('/');
        $response->assertStatus(200);

        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->get('/login');
        $response->assertStatus(200);

        // Start with protected routes
        $user = $this->createLoggedUser();

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/time-entries');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/clients');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/projects');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/work-types');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/reporting');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/settings');
        $response->assertStatus(200);
    }

    #[Group('routing')]
    public function test_all_screens_redirect_unauthenticated_users(): void
    {
        $response = $this->get('/dashboard');
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $response = $this->get('/time-entries');
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $response = $this->get('/clients');
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $response = $this->get('/projects');
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $response = $this->get('/work-types');
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $response = $this->get('/reporting');
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $response = $this->get('/settings');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    #[Group('routing')]
    public function test_admin_screens_can_be_rendered_for_authenticated_users_that_are_admin(): void
    {
        $user = $this->createLoggedAdminUser();

        $response = $this->actingAs($user)->get('/users');
        $response->assertStatus(200);
    }

    #[Group('routing')]
    public function test_admin_screens_cannot_be_rendered_for_authenticated_users_that_are_not_admin(): void
    {
        $user = $this->createLoggedUser();

        $response = $this->actingAs($user)->get('/users');
        $response->assertStatus(302);
    }

    #[Group('routing')]
    public function test_admin_screens_cannot_be_rendered_for_unauthenticated_users(): void
    {
        $response = $this->get('/users');
        $response->assertStatus(302);
    }
}
