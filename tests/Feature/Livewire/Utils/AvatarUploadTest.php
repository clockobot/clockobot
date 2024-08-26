<?php

namespace Tests\Feature\Livewire\Utils;

use App\Livewire\Utils\AvatarUpload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class AvatarUploadTest extends TestCase
{
    use RefreshDatabase;

    #[Group('avatar')]
    public function test_avatar_can_be_updated(): void
    {
        Storage::fake('public');

        $user = $this->createLoggedUser();

        $file = UploadedFile::fake()->create('avatar.png', 100, 'image/png');

        Livewire::actingAs($user)
            ->test(AvatarUpload::class, ['user' => $user])
            ->set('photo', $file)
            ->assertHasNoErrors()
            ->assertDispatched('notify');

        $this->assertNotNull($user->fresh()->avatar);

        Storage::disk('tmp-for-tests')->assertExists('public/'.$user->fresh()->avatar);
    }

    #[Group('avatar')]
    public function test_avatar_deletion(): void
    {
        $userWithAvatar = $this->createLoggedUser();

        $file1 = UploadedFile::fake()->create('existing_avatar.png', 100, 'image/png');
        $file2 = UploadedFile::fake()->create('existing_avatar.png', 100, 'image/png');

        Livewire::actingAs($userWithAvatar)
            ->test(AvatarUpload::class, ['user' => $userWithAvatar])
            ->set('photo', $file1)
            ->assertHasNoErrors();

        $this->assertNotNull($userWithAvatar->fresh()->avatar);
        Storage::disk('tmp-for-tests')->assertExists('public/'.$userWithAvatar->fresh()->avatar);

        $filepath1 = $userWithAvatar->fresh()->avatar;

        Livewire::actingAs($userWithAvatar)
            ->test(AvatarUpload::class, ['user' => $userWithAvatar])
            ->set('photo', $file2)
            ->assertHasNoErrors();

        $this->assertNotEquals($filepath1, $userWithAvatar->fresh()->avatar);
        Storage::disk('public')->assertMissing('public/'.$filepath1);

        $this->assertNotNull($userWithAvatar->fresh()->avatar);
        Storage::disk('tmp-for-tests')->assertExists('public/'.$userWithAvatar->fresh()->avatar);
    }
}
