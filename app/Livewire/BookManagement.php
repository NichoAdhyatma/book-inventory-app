<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;

class BookManagement extends Component
{
    use WithFileUploads, WithPagination;

    public $book_id;
    
    #[Rule('required|string|max:255')]
    public $title = '';
    
    #[Rule('required|string|max:255')]
    public $author = '';
    
    #[Rule('required|string')]
    public $description = '';
    
    #[Rule('required|integer|min:1|max:5')]
    public $rating = 3;
    
    #[Rule('nullable|image|max:2048')]
    public $thumbnail;
    
    public $show_modal = false;
    public $editing = false;
    public $search = '';

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->book_id = null;
        $this->title = '';
        $this->author = '';
        $this->description = '';
        $this->rating = 3;
        $this->thumbnail = null;
        $this->editing = false;
    }

    public function openModal($book_id = null)
    {
        $this->resetForm();
        
        if ($book_id) {
            $book = Book::findOrFail($book_id);
            $this->book_id = $book->id;
            $this->title = $book->title;
            $this->author = $book->author;
            $this->description = $book->description;
            $this->rating = $book->rating;
            $this->editing = true;
        }
        
        $this->show_modal = true;
    }

    public function closeModal()
    {
        $this->show_modal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function save()
    {
        try {
            $this->validate();

            $data = [
                'title' => $this->title,
                'author' => $this->author,
                'description' => $this->description,
                'rating' => $this->rating,
                'user_id' => auth()->id(),
            ];

            if ($this->thumbnail) {
                $thumbnail_path = $this->thumbnail->store('thumbnails', 'public');
                $data['thumbnail'] = $thumbnail_path;
            }

            if ($this->editing) {
                $book = Book::findOrFail($this->book_id);
                
                if ($this->thumbnail && $book->thumbnail) {
                    Storage::disk('public')->delete($book->thumbnail);
                }
                
                $book->update($data);
                session()->flash('success', 'Book updated successfully!');
            } else {
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

    public function render()
    {
        $books = Book::where('user_id', auth()->id())
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'ILIKE', '%' . $this->search . '%')
                      ->orWhere('author', 'ILIKE', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.book-management', compact('books'));
    }
}