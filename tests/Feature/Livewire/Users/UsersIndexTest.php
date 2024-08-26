<?php

namespace Tests\Feature\Livewire\Users;

use App\Livewire\Users\UsersIndex;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class UsersIndexTest extends TestCase
{
    use RefreshDatabase;

    #[Group('users')]
    public function test_users_index_component(): void
    {
        User::factory(20)->create();

        Livewire::test(UsersIndex::class)
            ->assertHasNoErrors()
            ->assertSet('query', '')
            ->assertSet('users', function ($users) {
                return $users->count() === 15;
            });
    }

    #[Group('users')]
    public function test_users_can_search_users_on_index_component(): void
    {
        User::factory()->create(['name' => 'User 1']);
        User::factory()->create(['name' => 'User 2']);
        User::factory()->create(['name' => 'User 3']);

        Livewire::test(UsersIndex::class)
            ->assertHasNoErrors()
            ->set('query', 'User')
            ->assertCount('users', 3);

        Livewire::test(UsersIndex::class)
            ->assertHasNoErrors()
            ->set('query', 'User 2')
            ->assertCount('users', 1);

        Livewire::test(UsersIndex::class)
            ->assertHasNoErrors()
            ->set('query', 'User 4')
            ->assertCount('users', 0);
    }
}
