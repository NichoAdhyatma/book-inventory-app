<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public $search = '';
    public $filter_verified = '';
    public $per_page = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'filter_verified' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterVerified()
    {
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
                } else {
                    $query->whereNull('email_verified_at');
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->per_page);

        return view('livewire.user-list', compact('users'));
    }
}