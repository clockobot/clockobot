<?php

namespace Tests\Feature\API;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $headers = ['Accept' => 'application/json'];

    #[Group('api_clients')]
    public function test_list_clients(): void
    {
        $this->createLoggedUser();

        $clients = Client::factory()->count(5)->create();
        $response = $this->json('GET', '/api/clients/list');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success' => [
                '*' => [
                    'id',
                    'name',
                ],
            ],
        ]);

        $response->assertJsonCount(5, 'success');

        foreach ($clients as $client) {
            // Assert data is correct
            $response->assertJsonFragment($client->toArray());
        }
    }

    #[Group('api_clients')]
    public function test_create_client(): void
    {
        $this->createLoggedUser();

        $data = [
            'name' => 'Test Client',
            'contact_name' => 'John Doe',
            'contact_email' => 'john.doe@example.com',
            'contact_phone' => '123-456-7890',
        ];

        $response = $this->json('POST', '/api/clients/create', $data);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success' => [
                'id',
                'name',
                'contact_name',
                'contact_email',
                'contact_phone',
                'created_at',
                'updated_at',
            ],
        ]);

        $this->assertDatabaseHas('clients', [
            'name' => 'Test Client',
            'contact_name' => 'John Doe',
            'contact_email' => 'john.doe@example.com',
            'contact_phone' => '123-456-7890',
        ]);
    }

    #[Group('api_clients')]
    public function test_create_client_with_failed_validation(): void
    {
        $this->createLoggedUser();

        $data = [
            'name' => 'Test Client',
            'contact_name' => 'John Doe',
            'contact_email' => 'invalid_email',
            'contact_phone' => '123-456-7890',
        ];

        $response = $this->json('POST', '/api/clients/create', $data);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'error' => [
                'contact_email',
            ],
        ]);

        $response->assertJsonFragment([
            'contact_email' => ['The contact email field must be a valid email address.'],
        ]);
    }

    #[Group('api_clients')]
    public function test_get_client(): void
    {
        $this->createLoggedUser();

        $client = Client::factory()->create();

        $response = $this->json('GET', '/api/clients/'.$client->id.'/details');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success' => [
                'id',
                'name',
                'contact_name',
                'contact_email',
                'contact_phone',
                'created_at',
                'updated_at',
            ],
        ]);

        $response->assertJson(['success' => $client->toArray()]);
    }

    #[Group('api_clients')]
    public function test_get_client_no_client(): void
    {
        $this->createLoggedUser();

        $response = $this->json('GET', '/api/clients/999/details'); // Assuming 999 is an ID that does not exist

        $response->assertStatus(404);

        $response->assertJsonFragment([
            'error' => 'Client not found',
        ]);
    }

    #[Group('api_clients')]
    public function test_update_client(): void
    {
        $this->createLoggedUser();
        $client = Client::factory()->create();

        $updatedData = [
            'name' => 'Updated Client Name',
            'contact_name' => 'Updated Contact Name',
            'contact_email' => 'updated@example.com',
            'contact_phone' => '1234567890',
        ];

        // Case 1: Testing when the client is not found
        $nonExistentClientId = 999; // ID does not exist
        $nonExistentClientData = [
            'name' => 'Non-Existent Client',
            'contact_name' => 'Non-Existent Contact',
            'contact_email' => 'nonexistent@example.com',
            'contact_phone' => '9876543210',
        ];

        $nonExistentClientResponse = $this->json('POST', "/api/clients/{$nonExistentClientId}/update", $nonExistentClientData);
        $nonExistentClientResponse->assertStatus(404);
        $nonExistentClientResponse->assertJson(['error' => 'Client not found']);

        // Case 2: Testing when the validator fails
        $invalidData = [
            'contact_name' => 'Invalid Contact Name',
            'contact_email' => 'invalid_example.com',
            'contact_phone' => '8765432109',
        ];

        $invalidDataResponse = $this->json('POST', "/api/clients/{$client->id}/update", $invalidData);
        $invalidDataResponse->assertStatus(422);
        $invalidDataResponse->assertJsonFragment([
            'contact_email' => ['The contact email field must be a valid email address.'],
        ]);
        $invalidDataResponse->assertJsonMissing(['success' => $invalidData]);

        $this->assertDatabaseMissing('clients', [
            'id' => $client->id,
            'contact_name' => $invalidData['contact_name'],
            'contact_email' => $invalidData['contact_email'],
            'contact_phone' => $invalidData['contact_phone'],
        ]);

        // Case 3: Testing a successful update
        $validDataResponse = $this->json('POST', "/api/clients/{$client->id}/update", $updatedData);
        $validDataResponse->assertStatus(200);
        $validDataResponse->assertJson(['success' => $updatedData]);

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'name' => $updatedData['name'],
            'contact_name' => $updatedData['contact_name'],
            'contact_email' => $updatedData['contact_email'],
            'contact_phone' => $updatedData['contact_phone'],
        ]);
    }

    #[Group('api_clients')]
    public function test_delete_client(): void
    {
        $this->createLoggedUser();

        $client = Client::factory()->create();

        $response = $this->json('POST', '/api/clients/'.$client->id.'/delete');
        $response->assertStatus(200);

        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }

    #[Group('api_clients')]
    public function test_delete_nonexistent_client(): void
    {
        $this->createLoggedUser();

        $response = $this->json('POST', '/api/clients/999/delete');

        $response->assertStatus(404);

        $response->assertJsonFragment([
            'error' => 'Client not found',
        ]);
    }

    #[Group('api_clients')]
    public function test_get_client_projects(): void
    {
        $this->createLoggedUser();
        $client = Client::factory()->create();
        Project::factory()->count(3)->create(['client_id' => $client->id]);

        $response = $this->json('GET', '/api/clients/'.$client->id.'/projects');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success' => [
                '*' => [
                    'id',
                    'title',
                ],
            ],
        ]);

        $response->assertJsonCount(3, 'success');
    }

    #[Group('api_clients')]
    public function test_get_projects_for_nonexistent_client(): void
    {
        $this->createLoggedUser();

        $response = $this->json('GET', '/api/clients/999/projects');
        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Client not found',
        ]);
    }

    #[Group('api_clients')]
    public function test_search_client_with_query(): void
    {
        $this->createLoggedUser();

        $clients = Client::factory()->count(3)->create();

        $response = $this->json('POST', '/api/clients/search', ['query' => $clients[0]->name]);

        $response->assertStatus(200);

        $response->assertJson(['success' => [$clients[0]->toArray()]]);
    }

    #[Group('api_clients')]
    public function test_search_client_without_query(): void
    {
        $this->createLoggedUser();

        $clients = Client::factory()->count(3)->create();

        $response = $this->json('POST', '/api/clients/search');

        $response->assertStatus(200);

        $response->assertJson(['success' => $clients->sortBy('name')->values()->toArray()]);
    }
}
