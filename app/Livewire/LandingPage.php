<?php

namespace App\Livewire;

use App\Models\Book;
use Livewire\Component;
use Livewire\WithPagination;

class LandingPage extends Component
{
    use WithPagination;

    public $filter_author = '';
    public $filter_rating = '';
    public $filter_date_from = '';
    public $filter_date_to = '';
    public $per_page = 12;

    protected $queryString = [
        'filter_author' => ['except' => ''],
        'filter_rating' => ['except' => ''],
        'filter_date_from' => ['except' => ''],
        'filter_date_to' => ['except' => ''],
    ];

    public function updatingFilterAuthor()
    {
        $this->resetPage();
    }

    public function updatingFilterRating()
    {
        $this->resetPage();
    }

    public function updatingFilterDateFrom()
    {
        $this->resetPage();
    }

    public function updatingFilterDateTo()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->filter_author = '';
        $this->filter_rating = '';
        $this->filter_date_from = '';
        $this->filter_date_to = '';
        $this->resetPage();
    }

    public function render()
    {
        $books = Book::with('user')
            ->filterByAuthor($this->filter_author)
            ->filterByRating($this->filter_rating)
            ->filterByDateRange($this->filter_date_from, $this->filter_date_to)
            ->orderBy('created_at', 'desc')
            ->paginate($this->per_page);

        $authors = Book::distinct()->pluck('author')->sort();

        return view('livewire.landing-page', compact('books', 'authors'))
            ->layout('layouts.guest');
    }
}