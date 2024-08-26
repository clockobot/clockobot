<?php

namespace Tests\Feature\Livewire\Reporting;

use App\Exports\ReportExport;
use App\Livewire\Reporting\ReportingIndex;
use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\User;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Maatwebsite\Excel\Facades\Excel;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class ReportingIndexTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Group('reporting')]
    public function test_reporting_index_component(): void
    {
        $this->createLoggedUser();

        $client = Client::factory()->create();
        Project::factory()->create(['client_id' => $client->id]);
        WorkType::factory()->create();

        TimeEntry::factory(10)->create([
            'start' => $this->faker->dateTimeBetween(now()->subHours(4), now()->subHours(2)),
            'end' => $this->faker->dateTimeBetween(now()->subHours(2), now()),
        ]);

        Livewire::test(ReportingIndex::class)
            ->assertCount('time_entries', 10)
            ->assertHasNoErrors();
    }

    #[Group('reporting')]
    public function test_reporting_index_client_filters(): void
    {
        $this->createLoggedUser();

        $client = Client::factory()->create();
        $client2 = Client::factory()->create();

        $project = Project::factory()->create(['client_id' => $client->id]);
        $project2 = Project::factory()->create(['client_id' => $client2->id]);

        WorkType::factory()->create();

        TimeEntry::factory(5)->create([
            'client_id' => $client->id,
            'project_id' => $project->id,
            'start' => $this->faker->dateTimeBetween(now()->subHours(4), now()->subHours(2)),
            'end' => $this->faker->dateTimeBetween(now()->subHours(2), now()),
        ]);
        TimeEntry::factory(5)->create([
            'client_id' => $client2->id,
            'project_id' => $project2->id,
            'start' => $this->faker->dateTimeBetween(now()->subHours(4), now()->subHours(2)),
            'end' => $this->faker->dateTimeBetween(now()->subHours(2), now()),
        ]);

        Livewire::test(ReportingIndex::class, ['client_id' => $client->id])
            ->assertCount('time_entries', 5)
            ->assertHasNoErrors();
    }

    #[Group('reporting')]
    public function test_reporting_index_project_filter(): void
    {
        $this->createLoggedUser();

        $client = Client::factory()->create();
        $client2 = Client::factory()->create();

        $project = Project::factory()->create(['client_id' => $client->id]);
        $project2 = Project::factory()->create(['client_id' => $client2->id]);

        WorkType::factory()->create();

        TimeEntry::factory(5)->create([
            'client_id' => $client->id,
            'project_id' => $project->id,
            'start' => $this->faker->dateTimeBetween(now()->subHours(4), now()->subHours(2)),
            'end' => $this->faker->dateTimeBetween(now()->subHours(2), now()),
        ]);
        TimeEntry::factory(5)->create([
            'client_id' => $client2->id,
            'project_id' => $project2->id,
            'start' => $this->faker->dateTimeBetween(now()->subHours(4), now()->subHours(2)),
            'end' => $this->faker->dateTimeBetween(now()->subHours(2), now()),
        ]);

        Livewire::test(ReportingIndex::class, ['project_id' => $project->id])
            ->assertCount('time_entries', 5)
            ->assertHasNoErrors();
    }

    #[Group('reporting')]
    public function test_reporting_index_user_filter(): void
    {
        $user = User::factory()->create();
        $user2 = $this->createLoggedUser();

        $client = Client::factory()->create();
        $client2 = Client::factory()->create();

        $project = Project::factory()->create(['client_id' => $client->id]);
        $project2 = Project::factory()->create(['client_id' => $client2->id]);

        WorkType::factory()->create();

        TimeEntry::factory(5)->create([
            'client_id' => $client->id,
            'project_id' => $project->id,
            'user_id' => $user->id,
            'start' => $this->faker->dateTimeBetween(now()->subHours(4), now()->subHours(2)),
            'end' => $this->faker->dateTimeBetween(now()->subHours(2), now()),
        ]);
        TimeEntry::factory(5)->create([
            'client_id' => $client2->id,
            'project_id' => $project2->id,
            'user_id' => $user2->id,
            'start' => $this->faker->dateTimeBetween(now()->subHours(4), now()->subHours(2)),
            'end' => $this->faker->dateTimeBetween(now()->subHours(2), now()),
        ]);

        Livewire::test(ReportingIndex::class, ['user_id' => $user->id])
            ->assertCount('time_entries', 5)
            ->assertHasNoErrors();
    }

    #[Group('reporting')]
    public function test_users_can_reload_the_page_and_reset_filters_on_reporting_index(): void
    {
        $user = $this->createLoggedUser();

        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client->id]);

        WorkType::factory()->create();
        TimeEntry::factory(5)->create([
            'client_id' => $client->id,
            'project_id' => $project->id,
            'user_id' => $user->id,
            'start' => $this->faker->dateTimeBetween(now()->subHours(4), now()->subHours(2)),
            'end' => $this->faker->dateTimeBetween(now()->subHours(2), now()),
        ]);

        Livewire::test(ReportingIndex::class, ['user_id' => $user->id])
            ->assertCount('time_entries', 5)
            ->assertHasNoErrors()
            ->call('resetData')
            ->assertStatus(200);
    }

    #[Group('reporting')]
    public function test_user_can_download_timesheet_report(): void
    {
        $this->createLoggedUser();

        $client = Client::factory()->create();

        Project::factory()->create(['client_id' => $client->id]);
        WorkType::factory()->create();
        TimeEntry::factory(5)->create();

        Excel::fake();

        Livewire::test(ReportingIndex::class)
            ->call('export')
            ->assertStatus(200);

        Excel::assertDownloaded('report.xlsx', function (ReportExport $export) {
            // Assert that the correct export is downloaded by matching the view name returned (need to return a true/false condition)
            return $export->view()->name() === 'exports.timesheet';
        });
    }
}
