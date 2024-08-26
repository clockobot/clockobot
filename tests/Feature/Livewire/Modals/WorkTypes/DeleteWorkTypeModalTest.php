<?php

namespace Tests\Feature\Livewire\Modals\WorkTypes;

use App\Livewire\Modals\WorkTypes\DeleteWorkTypeModal;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class DeleteWorkTypeModalTest extends TestCase
{
    use RefreshDatabase;

    #[Group('work_types')]
    public function test_users_can_add_a_client(): void
    {
        $work_type = WorkType::factory()->create();

        Livewire::test(DeleteWorkTypeModal::class, [
            'id' => $work_type->id,
        ])
            ->call('confirmDelete')
            ->assertHasNoErrors()
            ->assertDispatched('refreshWorkTypesList')
            ->assertDispatched('notify');
    }
}
