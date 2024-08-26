<?php

namespace Tests\Feature\API;

use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class TimeEntryControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Group('api_time_entries')]
    public function test_list_time_entries(): void
    {
        $user = $this->createLoggedUser();

        $client = Client::factory()->create();
        $project = Project::factory()->create();
        $work_type = WorkType::factory()->create();

        TimeEntry::factory()->create([
            'client_id' => $client->id,
            'project_id' => $project->id,
            'work_type_id' => $work_type->id,
            'user_id' => $user->id,
            'start' => now()->subDays(3)->format('Y-m-d H:i'),
            'end' => now()->subDays(2)->format('Y-m-d H:i'),
        ]);

        $response = $this->get('/api/time-entries/list');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success' => [
                '*' => [
                    'id',
                    'client_id',
                ],
            ],
        ]);
    }

    #[Group('api_time_entries')]
    public function test_init_time_entry_with_no_active_time_entry(): void
    {
        $user = $this->createLoggedUser();

        Client::factory()->create();
        Project::factory()->create();
        WorkType::factory()->create();

        $response = $this->post('/api/time-entries/init', [
            'start' => now()->format('Y-m-d H:i:s'),
            'user_id' => $user->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('time_entries', [
            'user_id' => $user->id,
            'end' => null,
        ]);
    }

    #[Group('api_time_entries')]
    public function test_init_time_entry_with_active_time_entry(): void
    {
        $user = $this->createLoggedUser();

        Client::factory()->create();
        Project::factory()->create();
        WorkType::factory()->create();
        TimeEntry::factory()->create([
            'user_id' => $user->id,
            'end' => null,
        ]);

        $response = $this->post('/api/time-entries/init', [
            'start' => now()->format('Y-m-d H:i:s'),
            'user_id' => $user->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonFragment(['user_id' => $user->id, 'end' => null]);
    }

    #[Group('api_time_entries')]
    public function test_init_time_entry_with_validation_errors(): void
    {
        $user = $this->createLoggedUser();

        $client = Client::factory()->create();
        $project = Project::factory()->create();
        $work_type = WorkType::factory()->create();

        $response = $this->post('/api/time-entries/init', [
            'user_id' => $user->id,
            'client_id' => $client->id,
            'project_id' => $project->id,
            'work_type_id' => $work_type->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['error' => ['start']]);
        $response->assertJsonFragment(['start' => ['The start field is required.']]);
        $response->assertJsonMissing(['success']);
    }

    #[Group('api_time_entries')]
    public function test_create_time_entry_with_valid_data(): void
    {
        $user = $this->createLoggedUser();

        $client = Client::factory()->create();
        $project = Project::factory()->create();
        $work_type = WorkType::factory()->create();

        $response = $this->post('/api/time-entries/create', [
            'start' => now()->subDays(5)->format('Y-m-d H:i:s'),
            'end' => now()->subDays(4)->endOfDay()->format('Y-m-d H:i:s'),
            'user_id' => $user->id,
            'client_id' => $client->id,
            'project_id' => $project->id,
            'work_type_id' => $work_type->id,
            'description' => 'Test Description',
            'link' => 'http://example.com',
            'billable' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('time_entries', [
            'user_id' => $user->id,
            'description' => 'Test Description',
        ]);
    }

    #[Group('api_time_entries')]
    public function test_create_time_entry_with_invalid_data(): void
    {
        $this->createLoggedUser();

        Client::factory()->create();
        Project::factory()->create();
        WorkType::factory()->create();

        $response = $this->post('/api/time-entries/create', [
            // Missing required fields
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['error']);
    }

    #[Group('api_time_entries')]
    public function test_get_time_entry_with_existing_id(): void
    {
        $this->createLoggedUser();

        Client::factory()->create();
        Project::factory()->create();
        WorkType::factory()->create();
        $timeEntry = TimeEntry::factory()->create();

        $response = $this->get('/api/time-entries/'.$timeEntry->id.'/details');

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonFragment(['id' => $timeEntry->id]);
    }

    #[Group('api_time_entries')]
    public function test_get_time_entry_with_nonexistent_id(): void
    {
        $this->createLoggedUser();

        $response = $this->get('/api/time-entries/4242/details');

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Time entry not found']);
    }

    #[Group('api_time_entries')]
    public function test_update_time_entry_with_valid_data(): void
    {
        $this->createLoggedUser();

        $client = Client::factory()->create();
        $project = Project::factory()->create();
        $work_type = WorkType::factory()->create();
        $timeEntry = TimeEntry::factory()->create();

        $response = $this->post('/api/time-entries/'.$timeEntry->id.'/update', [
            'start' => now()->subDays(3)->format('Y-m-d H:i:s'),
            'end' => now()->subDays(2)->endOfDay()->format('Y-m-d H:i:s'),
            'client_id' => $client->id,
            'project_id' => $project->id,
            'work_type_id' => $work_type->id,
            'description' => 'Updated Description',
            'billable' => false,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('time_entries', [
            'id' => $timeEntry->id,
            'description' => 'Updated Description',
        ]);
    }

    #[Group('api_time_entries')]
    public function test_update_time_entry_with_nonexistent_id(): void
    {
        $this->createLoggedUser();

        $response = $this->post('/api/time-entries/4242/update', []);

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Time entry not found']);
    }

    #[Group('api_time_entries')]
    public function test_update_time_entry_with_invalid_data(): void
    {
        $this->createLoggedUser();

        Client::factory()->create();
        Project::factory()->create();
        WorkType::factory()->create();
        $timeEntry = TimeEntry::factory()->create();

        $response = $this->post('/api/time-entries/'.$timeEntry->id.'/update', [
            'client_id' => 'invalid id',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['error']);
    }

    #[Group('api_time_entries')]
    public function test_delete_time_entry_with_existing_id(): void
    {
        $this->createLoggedUser();

        Client::factory()->create();
        Project::factory()->create();
        WorkType::factory()->create();
        $timeEntry = TimeEntry::factory()->create();

        $response = $this->post('/api/time-entries/'.$timeEntry->id.'/delete');

        $response->assertStatus(200); // as user is redirected at this point
        $this->assertDatabaseMissing('time_entries', ['id' => $timeEntry->id]);
    }

    #[Group('api_time_entries')]
    public function test_delete_time_entry_with_nonexistent_id(): void
    {
        $this->createLoggedUser();

        $response = $this->post('/api/time-entries/4242/delete');

        $response->assertStatus(404);

        $response->assertJson(['error' => 'Time entry not found']);
    }

    #[Group('api_time_entries')]
    public function test_get_time_entry_decimal_duration_with_existing_id(): void
    {
        $this->createLoggedUser();

        Client::factory()->create();
        Project::factory()->create();
        WorkType::factory()->create();

        $timeEntry = TimeEntry::factory()->create([
            'start' => now()->subHours(3)->format('Y-m-d H:i:s'),
            'end' => now()->format('Y-m-d H:i:s'),
        ]);

        $response = $this->get('/api/time-entries/'.$timeEntry->id.'/decimal-duration');

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonFragment(['success' => $timeEntry->calculateDurationInDecimal()]);
    }

    #[Group('api_time_entries')]
    public function test_get_time_entry_decimal_duration_with_nonexistent_id(): void
    {
        $this->createLoggedUser();

        $response = $this->get('/api/time-entries/4242/decimal-duration');

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Time entry not found']);
    }

    #[Group('api_time_entries')]
    public function test_get_time_entry_duration_in_hours_with_existing_id(): void
    {
        $this->createLoggedUser();

        Client::factory()->create();
        Project::factory()->create();
        WorkType::factory()->create();

        $timeEntry = TimeEntry::factory()->create([
            'start' => now()->subHours(3)->format('Y-m-d H:i:s'),
            'end' => now()->format('Y-m-d H:i:s'),
        ]);

        $response = $this->get('/api/time-entries/'.$timeEntry->id.'/hours-duration');

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonFragment(['success' => $timeEntry->calculateDurationInHours()]);
    }

    #[Group('api_time_entries')]
    public function test_get_time_entry_duration_in_hours_with_nonexistent_id(): void
    {
        $this->createLoggedUser();

        $response = $this->get('/api/time-entries/4242/hours-duration');

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Time entry not found']);
    }

    #[Group('api_time_entries')]
    public function test_get_ongoing_time_entry_for_user_with_existing_entries(): void
    {
        $user = $this->createLoggedUser();

        Client::factory()->create();
        Project::factory()->create();
        WorkType::factory()->create();

        $ongoingTimeEntry = TimeEntry::factory()->create([
            'user_id' => $user->id,
            'end' => null,
        ]);

        $response = $this->get('/api/time-entries/'.$user->id.'/ongoing');

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonFragment(['id' => $ongoingTimeEntry->id]);
    }

    #[Group('api_time_entries')]
    public function test_get_ongoing_time_entry_for_user_with_no_entries(): void
    {
        $this->createLoggedUser();

        $response = $this->get('/api/time-entries/4242/ongoing');

        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => []]);
        $response->assertJson(['success' => []]);
        $this->assertDatabaseMissing('time_entries', ['id' => 4242]);
    }
}
