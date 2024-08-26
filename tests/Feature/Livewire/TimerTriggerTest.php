<?php

namespace Tests\Feature\Livewire;

use App\Livewire\TimerTrigger;
use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class TimerTriggerTest extends TestCase
{
    use RefreshDatabase;

    #[Group('timer_trigger')]
    public function test_timer_trigger_component_cannot_be_triggered_when_missing_base(): void
    {
        Livewire::test(TimerTrigger::class)
            ->assertSet('disabledTimer', true)
            ->assertSet('started', false)
            ->assertHasNoErrors();
    }

    #[Group('timer_trigger')]
    public function test_timer_trigger_component_can_be_triggered(): void
    {
        $client = Client::factory()->create();
        Project::factory()->create(['client_id' => $client->id]);
        WorkType::factory()->create();

        Livewire::test(TimerTrigger::class)
            ->assertSet('disabledTimer', false)
            ->assertSet('started', false)
            ->assertHasNoErrors();
    }

    #[Group('timer_trigger')]
    public function test_timer_trigger_component_gets_ongoing_user_entry_if_exists(): void
    {
        $user = $this->createLoggedUser();

        $client = Client::factory()->create();
        Project::factory()->create(['client_id' => $client->id]);
        WorkType::factory()->create();
        TimeEntry::factory()->create([
            'user_id' => $user->id,
            'start' => now()->format('Y-m-d H:i'),
            'end' => null,
        ]);

        Livewire::test(TimerTrigger::class)
            ->assertSet('disabledTimer', false)
            ->assertSet('started', true)
            ->assertSet('timeEntry', TimeEntry::where('user_id', $user->id)->whereNull('end')->first())
            ->assertHasNoErrors();
    }

    #[Group('timer_trigger')]
    public function test_users_can_start_timer_on_timer_trigger_component(): void
    {
        $this->createLoggedUser();

        $client = Client::factory()->create();
        Project::factory()->create(['client_id' => $client->id]);
        WorkType::factory()->create();

        $test = Livewire::test(TimerTrigger::class)
            ->assertSet('disabledTimer', false)
            ->assertSet('started', false)
            ->call('startTimer')
            ->assertDispatched('refreshTimeTrigger')
            ->assertHasNoErrors();

        $test->dispatch('refreshTimeTrigger')
            ->assertDispatched('runTimer')
            ->assertSet('started', true)
            ->assertHasNoErrors();
    }

    #[Group('timer_trigger')]
    public function test_users_cannot_see_timer_trigger_if_missing_base_even_if_entry_started_somehow(): void
    {
        $user = $this->createLoggedUser();

        $client = Client::factory()->create();
        Project::factory()->create(['client_id' => $client->id]);
        WorkType::factory()->create();

        TimeEntry::factory()->create([
            'user_id' => $user->id,
            'start' => now()->format('Y-m-d H:i'),
            'end' => null,
        ]);

        WorkType::get()->first()->delete();

        Livewire::test(TimerTrigger::class)
            ->call('refreshTimeTrigger')
            ->assertSet('disabledTimer', true)
            ->assertSet('started', false)
            ->assertHasNoErrors();
    }

    #[Group('timer_trigger')]
    public function test_users_can_start_autotimer_from_the_dashboard(): void
    {
        $this->createLoggedUser();

        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client->id]);
        $work_type = WorkType::factory()->create();

        Livewire::test(TimerTrigger::class, [
            'client_id' => $client->id,
            'project_id' => $project->id,
            'work_type_id' => $work_type->id,
        ])
            ->call('startAutoTimer')
            ->assertDispatched('refreshTimeTrigger')
            ->assertHasNoErrors();
    }
}
