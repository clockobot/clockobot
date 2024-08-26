<?php

namespace Tests\Feature\API;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Group('api_users')]
    public function test_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('secret'),
        ]);

        $response = $this->post('/api/login', [
            'email' => 'test@example.com',
            'password' => 'secret',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonStructure(['success' => ['token']]);
        $response->assertJsonMissing(['error']);
    }

    #[Group('api_users')]
    public function test_login_with_invalid_credentials(): void
    {
        $response = $this->post('/api/login', [
            'email' => 'test@example.com',
            'password' => '123456',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['error' => 'Unauthorised']);
        $response->assertJsonMissing(['success']);
    }

    #[Group('api_users')]
    public function test_register_with_valid_data(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => 'secret',
            'c_password' => 'secret',
        ]);

        $response->assertStatus(200);

        $response->assertJson(['success' => true]);
        $response->assertJsonStructure(['success' => ['token', 'name']]);
        $response->assertJsonMissing(['error']);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'test@example.com',
        ]);
    }

    #[Group('api_users')]
    public function test_register_with_invalid_data(): void
    {
        $response = $this->post('/api/register', [
            // missing name
            'email' => 'test@example.com',
            'password' => 'password123',
            'c_password' => 'password123',
        ]);

        $response->assertStatus(401);
        $response->assertJsonStructure(['error' => ['name']]);
        $response->assertJsonMissing(['success']);
    }

    #[Group('api_users')]
    public function test_list_with_users(): void
    {
        $this->createLoggedUser();
        User::factory()->count(3)->create();

        $response = $this->get('/api/users/list');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success' => [
                '*' => [
                    'id',
                    'name',
                ],
            ],
        ]);
        $response->assertJsonMissing(['success' => []]);
    }

    #[Group('api_users')]
    public function test_list_with_no_users(): void
    {
        $user = $this->createLoggedUser();
        $user->delete();
        $response = $this->get('/api/users/list');

        $response->assertStatus(200);
        $response->assertJson(['success' => []]);
        $response->assertJsonMissing(['success']);
    }

    #[Group('api_users')]
    public function test_get_user_authenticated(): void
    {
        $user = $this->createLoggedUser();

        $response = $this->post('/api/details');

        $response->assertStatus(200);

        $response->assertJson(['success' => $user->toArray()]);
    }

    #[Group('api_users')]
    public function test_get_user_details_by_id_with_existing_user(): void
    {
        $user = $this->createLoggedUser();

        $response = $this->get('/api/users/'.$user->id);

        $response->assertStatus(200);
        $response->assertJson(['success' => $user->toArray()]);
    }

    #[Group('api_users')]
    public function test_get_user_details_by_id_with_nonexistent_user(): void
    {
        $this->createLoggedUser();

        $response = $this->get('/api/users/4242');

        $response->assertStatus(404);
        $response->assertJson(['error' => 'User not found']);
    }

    #[Group('api_users')]
    public function test_update_user_with_valid_data(): void
    {
        $user = $this->createLoggedUser();

        $response = $this->post('/api/users/'.$user->id, [
            'name' => 'Updated Name',
            'email' => 'test@example.com',
            'notifications' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => $user->fresh()->toArray()]);
        $response->assertJsonMissing(['success' => []]);
    }

    #[Group('api_users')]
    public function test_update_user_with_invalid_data(): void
    {
        $this->createLoggedUser();

        $response = $this->post('/api/users/4242', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(404);
        $response->assertJson(['error' => 'User not found']);
        $response->assertJsonMissing(['success']);
    }

    #[Group('api_users')]
    public function test_update_user_with_validation_errors(): void
    {
        $user = $this->createLoggedUser();

        $response = $this->post('/api/users/'.$user->id, [
            'name' => '', // Invalid name, required field is empty
            'email' => 'invalid-email', // Invalid email format
            'notifications' => 'not-a-boolean', // Invalid boolean value
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['error' => ['name', 'email', 'notifications']]);
        $response->assertJsonFragment(['name' => ['The name field is required.']]);
        $response->assertJsonFragment(['email' => ['The email field must be a valid email address.']]);
        $response->assertJsonFragment(['notifications' => ['The notifications field must be true or false.']]);
        $response->assertJsonMissing(['success']);
    }

    #[Group('api_users')]
    public function test_update_user_not_found(): void
    {
        $this->createLoggedUser();

        $response = $this->post('/api/users/4242', [
            'name' => 'Updated Name',
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(404);
        $response->assertJson(['error' => 'User not found']);
    }
}
