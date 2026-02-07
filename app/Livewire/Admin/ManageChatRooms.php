<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ChatRoom;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class ManageChatRooms extends Component
{
    use WithPagination;

    public $name;
    public $slug;
    public $description;
    public $roomId;
    public $isEditing = false;
    
    public $search = '';
    public $tab = 'rooms'; // 'rooms' or 'form'

    protected $rules = [
        'name' => 'required|min:3|max:50',
        'slug' => 'required|alpha_dash|unique:chat_rooms,slug',
        'description' => 'nullable|max:255',
    ];

    public function updatedName($value)
    {
        if (!$this->isEditing) {
            $this->slug = Str::slug($value);
        }
    }

    public function render()
    {
        $rooms = ChatRoom::query()
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('description', 'like', '%'.$this->search.'%');
            })
            ->withCount('posts')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.manage-chat-rooms', [
            'rooms' => $rooms
        ])->layout('layouts.site');
    }

    public function create()
    {
        $this->validate();

        ChatRoom::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'created_by' => auth()->id(),
        ]);

        $this->reset(['name', 'slug', 'description']);
        session()->flash('success', 'Chat room created successfully.');
        $this->tab = 'rooms'; // Switch back to list
    }

    public function edit($id)
    {
        $room = ChatRoom::findOrFail($id);
        $this->roomId = $id;
        $this->name = $room->name;
        $this->slug = $room->slug;
        $this->description = $room->description;
        $this->isEditing = true;
        
        $this->tab = 'form'; // Switch to form tab
        $this->resetValidation();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:3|max:50',
            'slug' => 'required|alpha_dash|unique:chat_rooms,slug,' . $this->roomId,
            'description' => 'nullable|max:255',
        ]);

        $room = ChatRoom::findOrFail($this->roomId);
        $room->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
        ]);

        $this->reset(['name', 'slug', 'description', 'roomId', 'isEditing']);
        session()->flash('success', 'Chat room updated successfully.');
        $this->tab = 'rooms'; // Switch back to list
    }

    public function delete($id)
    {
        // Check for existing posts before deleting? 
        // Logic in previous version allowed deletion, let's keep it simple for now or copy the check if needed.
        // Step 170 showed a check for posts. The user might want that.
        // Let's verify if I should include it. The previous version I saw in Step 203 didn't have it?
        // Wait, Step 203 delete() method:
        /*
            public function delete($id)
            {
                ChatRoom::findOrFail($id)->delete();
                session()->flash('success', 'Chat room deleted successfully.');
            }
        */
        // Okay, I will stick to what was in Step 203 to avoid introducing new behavior/errors, 
        // but if robustness is needed I should add the check. I'll stick to simple delete for now to fix the variable error.
        
        ChatRoom::findOrFail($id)->delete();
        session()->flash('success', 'Chat room deleted successfully.');
    }

    public function cancel()
    {
        $this->reset(['name', 'slug', 'description', 'roomId', 'isEditing']);
        $this->tab = 'rooms';
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
        if ($tab === 'create') {
            $this->reset(['name', 'slug', 'description', 'roomId', 'isEditing']);
            $this->tab = 'form';
        }
    }
}
