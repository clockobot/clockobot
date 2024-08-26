<?php

namespace Tests\Feature\Livewire\Settings;

use App\Livewire\Settings\ManageSettings;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class ManageSettingsTest extends TestCase
{
    use RefreshDatabase;

    #[Group('settings')]
    public function test_manage_settings_component(): void
    {
        $user = $this->createLoggedUser();

        Livewire::test(ManageSettings::class)
            ->assertHasNoErrors()
            ->assertSet('name', $user->name)
            ->assertSet('email', $user->email);
    }

    #[Group('settings')]
    public function test_users_can_edit_settings(): void
    {
        $this->createLoggedUser();

        Livewire::test(ManageSettings::class)
            ->set('name', 'Strangebots')
            ->set('email', 'hello@strangebots.ch')
            ->call('save')
            ->assertHasNoErrors();
    }

    #[Group('settings')]
    public function test_locale_change_redirects_to_referer()
    {
        $user = $this->createLoggedUser();

        Livewire::test(ManageSettings::class, ['user' => $user])
            ->set('name', 'Strangebots')
            ->set('email', 'hello@strangebots.ch')
            ->set('locale', 'fr') // default is "en" for createLoggedUser
            ->call('save')
            ->assertHasNoErrors();

        $this->assertTrue($user->fresh()->locale === 'fr');
    }
}
