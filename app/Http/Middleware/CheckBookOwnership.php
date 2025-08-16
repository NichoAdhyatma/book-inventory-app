<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Book;
use Symfony\Component\HttpFoundation\Response;

class CheckBookOwnership
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $book_id = $request->route('book');
        
        if ($book_id) {
            $book = Book::find($book_id);
            
            if (!$book) {
                abort(404, 'Book not found.');
            }
            
            if ($book->user_id !== auth()->id()) {
                abort(403, 'You do not have permission to access this book.');
            }
        }

        return $next($request);
    }
}