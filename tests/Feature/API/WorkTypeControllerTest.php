<?php

namespace Tests\Feature\API;

use App\Models\Client;
use App\Models\Project;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class WorkTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Group('api_work_types')]
    public function test_list_work_types(): void
    {
        $this->createLoggedUser();
        Client::factory()->create();
        Project::factory()->create();

        $response = $this->json('GET', '/api/work-types/list');

        $response->assertJsonStructure([
            'success' => [
                '*' => [
                    'id',
                    'title',
                ],
            ],
        ]);
    }

    #[Group('api_work_types')]
    public function test_create_work_type_with_valid_data(): void
    {
        $this->createLoggedUser();

        $response = $this->post('/api/work-types/create', [
            'title' => 'Test',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => WorkType::first()->toArray()]);
    }

    #[Group('api_work_types')]
    public function test_create_work_type_with_invalid_data(): void
    {
        $this->createLoggedUser();

        $response = $this->post('/api/work-types/create', []);

        $response->assertStatus(422);
        $response->assertJsonStructure(['error' => ['title']]);
    }

    #[Group('api_work_types')]
    public function test_get_work_type_with_existing_work_type(): void
    {
        $this->createLoggedUser();
        $workType = WorkType::factory()->create();

        $response = $this->get('/api/work-types/'.$workType->id.'/details');

        $response->assertStatus(200);
        $response->assertJson(['success' => $workType->toArray()]);
    }

    #[Group('api_work_types')]
    public function test_get_work_type_with_nonexistent_work_type(): void
    {
        $this->createLoggedUser();

        $response = $this->get('/api/work-types/4242/details');

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Work type not found']);
        $response->assertJsonMissing(['success']);
    }

    #[Group('api_work_types')]
    public function test_update_work_type_with_valid_data(): void
    {
        $this->createLoggedUser();
        $workType = WorkType::factory()->create();

        $response = $this->post('/api/work-types/'.$workType->id.'/update', [
            'title' => 'Updated Work Type',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => $workType->fresh()->toArray()]);
        $response->assertJsonMissing(['success' => []]);
    }

    #[Group('api_work_types')]
    public function test_update_work_type_with_nonexistent_work_type(): void
    {
        $this->createLoggedUser();

        $response = $this->post('/api/work-types/4242/update', [
            'title' => 'Updated Work Type',
        ]);

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Work type not found']);
        $response->assertJsonMissing(['success']);
    }

    #[Group('api_work_types')]
    public function test_update_work_type_with_invalid_data(): void
    {
        $this->createLoggedUser();
        $workType = WorkType::factory()->create();

        $response = $this->post('/api/work-types/'.$workType->id.'/update', []);

        $response->assertStatus(422);
        $response->assertJsonStructure(['error' => ['title']]);
        $response->assertJsonMissing(['success']);
    }

    #[Group('api_work_types')]
    public function test_delete_work_type_with_existing_work_type(): void
    {
        $this->createLoggedUser();
        $workType = WorkType::factory()->create();

        $response = $this->post('/api/work-types/'.$workType->id.'/delete');

        $response->assertStatus(200);
        $this->assertNull(WorkType::find($workType->id));
    }

    #[Group('api_work_types')]
    public function test_delete_work_type_with_nonexistent_work_type(): void
    {
        $this->createLoggedUser();

        $response = $this->post('/api/work-types/4242/delete');

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Work type not found']);
    }

    #[Group('api_work_types')]
    public function test_search_work_type_with_query(): void
    {
        $this->createLoggedUser();
        $workType1 = WorkType::factory()->create(['title' => 'Dev']);
        $workType2 = WorkType::factory()->create(['title' => 'Design']);

        $response = $this->post('/api/work-types/search', [
            'query' => 'Des',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => [$workType2->toArray()]]);
        $response->assertJsonMissing(['success' => [$workType1->toArray()]]);
    }

    #[Group('api_work_types')]
    public function test_search_work_type_without_query(): void
    {
        $this->createLoggedUser();
        $workType1 = WorkType::factory()->create(['title' => 'Dev']);
        $workType2 = WorkType::factory()->create(['title' => 'Design']);

        $response = $this->post('/api/work-types/search', []);

        $response->assertStatus(200);
        $response->assertJson(['success' => [$workType2->toArray(), $workType1->toArray()]]); // cf: because of alphabetical order orderBy

        $this->assertCount(2, $response->json('success'));
    }
}
