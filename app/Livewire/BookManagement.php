<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;

class BookManagement extends Component
{
    use WithFileUploads, WithPagination;

    // Modal & Form Properties
    public $book_id;
    public $show_modal = false;
    public $editing = false;
    public $viewing = false;
    
    // Form Fields
    #[Rule('required|string|max:255')]
    public $title = '';
    
    #[Rule('required|string|max:255')]
    public $author = '';
    
    #[Rule('required|string|min:10')]
    public $description = '';
    
    #[Rule('required|integer|min:1|max:5')]
    public $rating = 3;
    
    #[Rule('nullable|image|max:2048')]
    public $thumbnail;
    
    public $existing_thumbnail;
    
    // Search & Filter
    #[Url(history: true)]
    public $search = '';
    
    #[Url(history: true)]
    public $sort_by = 'created_at';
    
    #[Url(history: true)]
    public $sort_direction = 'desc';
    
    public $per_page = 12;

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'book_id',
            'title',
            'author', 
            'description',
            'rating',
            'thumbnail',
            'existing_thumbnail',
            'editing',
            'viewing'
        ]);
        $this->resetValidation();
    }

    public function openModal($book_id = null, $view_only = false)
    {

        
        $this->resetForm();
        $this->viewing = $view_only;
        
        if ($book_id) {
            $book = Book::where('user_id', auth()->id())->findOrFail($book_id);
            $this->book_id = $book->id;
            $this->title = $book->title;
            $this->author = $book->author;
            $this->description = $book->description;
            $this->rating = $book->rating;
            $this->existing_thumbnail = $book->thumbnail;
            $this->editing = !$view_only;
        }
        
        $this->show_modal = true;
    }

    public function closeModal()
    {
        $this->show_modal = false;
        $this->resetForm();
    }

    public function save()
    {
        if ($this->viewing) return;
        
        try {
            $this->validate();

            $data = [
                'title' => $this->title,
                'author' => $this->author,
                'description' => $this->description,
                'rating' => $this->rating,
            ];

            if ($this->thumbnail) {
                $thumbnail_path = $this->thumbnail->store('thumbnails', 'public');
                $data['thumbnail'] = $thumbnail_path;
            }

            if ($this->editing && $this->book_id) {
                $book = Book::where('user_id', auth()->id())->findOrFail($this->book_id);
                
                if ($this->thumbnail && $book->thumbnail) {
                    Storage::disk('public')->delete($book->thumbnail);
                }
                
                $book->update($data);
                session()->flash('success', 'Book updated successfully!');
            } else {
                $data['user_id'] = auth()->id();
                Book::create($data);
                session()->flash('success', 'Book added successfully!');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred. Please try again.');
        }
    }

    public function delete($book_id)
    {
        try {
            $book = Book::where('user_id', auth()->id())->findOrFail($book_id);
            
            if ($book->thumbnail) {
                Storage::disk('public')->delete($book->thumbnail);
            }
            
            $book->delete();
            session()->flash('success', 'Book deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete book.');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sort_by === $field) {
            $this->sort_direction = $this->sort_direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort_by = $field;
            $this->sort_direction = 'asc';
        }
    }

    public function render()
    {
        $books = Book::where('user_id', auth()->id())
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'ILIKE', '%' . $this->search . '%')
                      ->orWhere('author', 'ILIKE', '%' . $this->search . '%')
                      ->orWhere('description', 'ILIKE', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sort_by, $this->sort_direction)
            ->paginate($this->per_page);

        return view('livewire.book-management', compact('books'))
            ->layout('layouts.app');
    }
}