<?php

namespace App\Livewire\Utils;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class AvatarUpload extends Component
{
    use WithFileUploads;

    public $photo;

    public User $user;

    public function mount($user = null)
    {
        $this->user = $user;
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:20000',
        ]);

        if ($this->user->avatar) {
            Storage::delete('public/'.$this->user->avatar);
        }

        $storedPhoto = $this->photo->store('public/avatars');
        $photoNameWithExtension = basename($storedPhoto);

        $this->user->update([
            'avatar' => 'avatars/'.$photoNameWithExtension,
        ]);

        $this->dispatch('notify', ['type' => 'success', 'message' => __('notifications.avatar_updated')]);
    }

    public function render()
    {
        return view('livewire.utils.avatar-upload');
    }
}
