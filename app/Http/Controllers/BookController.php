<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of books (public).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Book::with('user:id,name,email');

        // Filter by author
        if ($request->has('author') && $request->author) {
            $query->where('author', 'ILIKE', '%' . $request->author . '%');
        }

        // Filter by rating
        if ($request->has('rating') && $request->rating) {
            $query->where('rating', $request->rating);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        // Sort
        $sort_by = $request->get('sort_by', 'created_at');
        $sort_order = $request->get('sort_order', 'desc');
        $query->orderBy($sort_by, $sort_order);

        $books = $query->paginate($request->get('per_page', 12));

        return response()->json([
            'success' => true,
            'data' => $books,
            'message' => 'Books retrieved successfully'
        ]);
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book): JsonResponse
    {
        $book->load('user:id,name,email');

        return response()->json([
            'success' => true,
            'data' => $book,
            'message' => 'Book retrieved successfully'
        ]);
    }

    /**
     * Store a newly created book.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $validator->validated();
            $data['user_id'] = auth()->id();

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
            }

            $book = Book::create($data);
            $book->load('user:id,name,email');

            return response()->json([
                'success' => true,
                'data' => $book,
                'message' => 'Book created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create book',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified book.
     */
    public function update(Request $request, Book $book): JsonResponse
    {
        // Check ownership
        if ($book->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $validator->validated();

            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail
                if ($book->thumbnail) {
                    Storage::disk('public')->delete($book->thumbnail);
                }
                $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
            }

            $book->update($data);
            $book->load('user:id,name,email');

            return response()->json([
                'success' => true,
                'data' => $book,
                'message' => 'Book updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update book',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified book.
     */
    public function destroy(Book $book): JsonResponse
    {
        // Check ownership
        if ($book->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        try {
            // Delete thumbnail
            if ($book->thumbnail) {
                Storage::disk('public')->delete($book->thumbnail);
            }

            $book->delete();

            return response()->json([
                'success' => true,
                'message' => 'Book deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete book',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get authenticated user's books.
     */
    public function myBooks(Request $request): JsonResponse
    {
        $query = Book::where('user_id', auth()->id());

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ILIKE', '%' . $search . '%')
                  ->orWhere('author', 'ILIKE', '%' . $search . '%');
            });
        }

        $books = $query->orderBy('created_at', 'desc')
                      ->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $books,
            'message' => 'Your books retrieved successfully'
        ]);
    }

    /**
     * Get all books (admin only).
     */
    public function allBooks(Request $request): JsonResponse
    {
        $query = Book::with('user:id,name,email');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ILIKE', '%' . $search . '%')
                  ->orWhere('author', 'ILIKE', '%' . $search . '%');
            });
        }

        $books = $query->orderBy('created_at', 'desc')
                      ->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $books,
            'message' => 'All books retrieved successfully'
        ]);
    }

    /**
     * Force delete book (admin only).
     */
    public function forceDestroy(Book $book): JsonResponse
    {
        try {
            // Delete thumbnail
            if ($book->thumbnail) {
                Storage::disk('public')->delete($book->thumbnail);
            }

            $book->delete();

            return response()->json([
                'success' => true,
                'message' => 'Book force deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete book',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}