<?php

namespace Tests\Feature\Livewire\Modals\WorkTypes;

use App\Livewire\Modals\WorkTypes\EditWorkTypeModal;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class EditWorkTypeModalTest extends TestCase
{
    use RefreshDatabase;

    #[Group('work_types')]
    public function test_users_can_edit_a_client(): void
    {
        $work_type = WorkType::factory()->create();

        Livewire::test(EditWorkTypeModal::class, [
            'id' => $work_type->id,
        ])
            ->set('title', 'Task 1')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('refreshWorkTypesList')
            ->assertDispatched('notify');
    }
}
