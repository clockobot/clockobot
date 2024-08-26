<?php

namespace Tests\Feature\API;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Group('api_projects')]
    public function test_list_projects(): void
    {
        $this->createLoggedUser();
        Client::factory()->create();
        Project::factory()->create();

        $response = $this->json('GET', '/api/projects/list');

        $response->assertJsonStructure([
            'success' => [
                '*' => [
                    'id',
                    'client_id',
                    'title',
                ],
            ],
        ]);
    }

    #[Group('api_projects')]
    public function test_create_project(): void
    {
        $this->createLoggedUser();
        $client = Client::factory()->create();

        $data = [
            'title' => 'New Project',
            'client_id' => $client->id,
            'description' => 'Project Description',
            'deadline' => '2023-12-31 00:00:00',
            'hour_estimate' => 10,
        ];

        $response = $this->json('POST', '/api/projects/create', $data);

        $response->assertJsonStructure([
            'success' => [
                'id',
                'title',
                'client_id',
                'description',
                'deadline',
                'hour_estimate',
                'created_at',
                'updated_at',
            ],
        ]);

        // Assert that the client was created in the database
        $this->assertDatabaseHas('projects', [
            'title' => 'New Project',
            'client_id' => $client->id,
            'description' => 'Project Description',
            'deadline' => '2023-12-31 00:00:00',
            'hour_estimate' => 10,
        ]);
    }

    #[Group('api_projects')]
    public function test_create_project_validation(): void
    {
        $this->createLoggedUser();

        $data = [
            // Missing title and client_id
            'description' => 'Project Description',
            'deadline' => '2023-12-31',
            'hour_estimate' => 10,
        ];

        $response = $this->json('POST', '/api/projects/create', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'error' => [
                'title',
                'client_id',
            ],
        ]);
    }

    #[Group('api_projects')]
    public function test_get_project(): void
    {
        $this->createLoggedUser();
        Client::factory()->create();
        $project = Project::factory()->create(['deadline' => null]); // faker can return unwanted format. Check in factory

        $response = $this->json('GET', "/api/projects/{$project->id}/details");

        $response->assertStatus(200);

        // Assert that the response structure is as expected
        $response->assertJsonStructure([
            'success' => [
                'id',
                'title',
                'client_id',
                'description',
                'deadline',
                'hour_estimate',
                'created_at',
                'updated_at',
            ],
        ]);

        $response->assertJson(['success' => $project->toArray()]);
    }

    #[Group('api_projects')]
    public function test_get_project_no_project(): void
    {
        $this->createLoggedUser();

        $response = $this->json('GET', '/api/projects/999/details'); // Assuming 999 is an ID that does not exist

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'error' => 'Project not found',
        ]);
    }

    #[Group('api_projects')]
    public function test_update_project(): void
    {
        $this->createLoggedUser();
        Client::factory()->create();
        $project = Project::factory()->create(['deadline' => null]); // faker can return unwanted format. Check in factory

        $data = [
            'title' => 'Updated Project',
            'client_id' => $project->client_id,
            'description' => 'Updated Project Description',
            'deadline' => '2023-12-31 00:00:00',
            'hour_estimate' => 15,
        ];

        $response = $this->json('POST', "/api/projects/{$project->id}/update", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => $data['title'],
        ]);
    }

    #[Group('api_projects')]
    public function test_update_project_not_found(): void
    {
        $this->createLoggedUser();

        $nonExistentProjectId = 999; // Assuming this ID does not exist

        $data = [
            'title' => 'Updated Project Title',
            'client_id' => 2,
            'description' => 'Updated Project Description',
            'deadline' => '2023-12-31',
            'hour_estimate' => 20,
        ];

        $response = $this->json('POST', '/api/projects/'.$nonExistentProjectId.'/update', $data);

        $response->assertStatus(404)->assertJson(['error' => 'Project not found']);
    }

    #[Group('api_projects')]
    public function test_update_project_validation(): void
    {
        $this->createLoggedUser();

        Client::factory()->create();
        $project = Project::factory()->create(['deadline' => null]); // faker can return unwanted format. Check in factory

        $invalidUpdateData = [
            // Missing title and client_id
            'description' => 'Updated Project Description',
            'deadline' => '2023-12-31',
            'hour_estimate' => 20,
        ];

        $response = $this->json('POST', '/api/projects/'.$project->id.'/update', $invalidUpdateData);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'error' => [
                'title',
                'client_id',
            ],
        ]);
    }

    #[Group('api_projects')]
    public function test_delete_project(): void
    {
        $this->createLoggedUser();
        Client::factory()->create();
        $project = Project::factory()->create(['deadline' => null]); // faker can return unwanted format. Check in factory

        $response = $this->json('POST', "/api/projects/{$project->id}/delete");

        $response->assertStatus(200);
    }

    #[Group('api_projects')]
    public function test_delete_nonexistent_client(): void
    {
        $this->createLoggedUser();

        $response = $this->json('POST', '/api/projects/999/delete');

        $response->assertStatus(404);

        $response->assertJsonFragment([
            'error' => 'Project not found',
        ]);
    }

    #[Group('api_projects')]
    public function test_search_project_with_query(): void
    {
        $this->createLoggedUser();
        Client::factory()->create();
        Project::factory()->create(['title' => 'Project Test', 'deadline' => null]); // faker can return unwanted format. Check in factory

        $response = $this->json('POST', '/api/projects/search', ['query' => 'Test']);

        $response->assertStatus(200);
    }

    #[Group('api_projects')]
    public function test_search_project_without_query(): void
    {
        $this->createLoggedUser();

        Client::factory(3)->create();
        $projects = Project::factory(3)->create(['deadline' => null]);

        $response = $this->json('POST', '/api/projects/search');

        $response->assertStatus(200);

        $response->assertJson(['success' => $projects->sortBy('title')->values()->toArray()]);
    }
}
