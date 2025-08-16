<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Book;

class Home extends Component
{
    public function render()
    {
        $user = auth()->user();
        $total_users = User::count();
        $verified_users = User::whereNotNull('email_verified_at')->count();
        $unverified_users = User::whereNull('email_verified_at')->count();
        $user_books_count = $user->books()->count();
        
        // Recent books
        $recent_books = Book::with('user')
            ->latest()
            ->take(5)
            ->get();
        
        // User's recent books
        $my_recent_books = $user->books()
            ->latest()
            ->take(5)
            ->get();
        
        return view('livewire.home', compact(
            'user', 
            'total_users', 
            'verified_users', 
            'unverified_users',
            'user_books_count',
            'recent_books',
            'my_recent_books'
        ))->layout('layouts.app');
    }
}