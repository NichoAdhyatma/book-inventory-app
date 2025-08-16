<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class UserList extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';
    
    #[Url(history: true)]
    public $filter_verified = '';
    
    public $per_page = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterVerified()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filter_verified = '';
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'ILIKE', '%' . $this->search . '%')
                      ->orWhere('email', 'ILIKE', '%' . $this->search . '%');
                });
            })
            ->when($this->filter_verified !== '', function ($query) {
                if ($this->filter_verified === '1') {
                    $query->whereNotNull('email_verified_at');
                } elseif ($this->filter_verified === '0') {
                    $query->whereNull('email_verified_at');
                }
            })
            ->withCount('books')
            ->orderBy('created_at', 'desc')
            ->paginate($this->per_page);

        return view('livewire.user-list', compact('users'))
            ->layout('layouts.app');
    }
}