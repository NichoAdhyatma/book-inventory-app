<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class UserController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            $user->sendEmailVerificationNotification();

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user->only(['id', 'name', 'email', 'email_verified_at']),
                ],
                'message' => 'Registration successful! Please verify your email.'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login user and return token.
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user->only(['id', 'name', 'email', 'email_verified_at']),
                    'token' => $token,
                    'token_type' => 'Bearer'
                ],
                'message' => 'Login successful'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout user (revoke token).
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout successful'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user profile.
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user->only(['id', 'name', 'email', 'email_verified_at', 'created_at']),
                'books_count' => $user->books()->count()
            ],
            'message' => 'Profile retrieved successfully'
        ]);
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'password' => 'nullable|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $data = $request->only(['name', 'email']);

            if ($request->filled('password')) {
                $data['password'] = $request->password;
            }

            // Check if email changed
            if ($user->email !== $request->email) {
                $data['email_verified_at'] = null;
            }

            $user->update($data);

            if (isset($data['email_verified_at']) && is_null($data['email_verified_at'])) {
                $user->sendEmailVerificationNotification();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user->only(['id', 'name', 'email', 'email_verified_at'])
                ],
                'message' => 'Profile updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all users (admin only).
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::query();

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', '%' . $search . '%')
                  ->orWhere('email', 'ILIKE', '%' . $search . '%');
            });
        }

        // Filter by verification status
        if ($request->has('verified') && $request->verified !== '') {
            if ($request->verified == '1') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->withCount('books')
                      ->orderBy('created_at', 'desc')
                      ->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'Users retrieved successfully'
        ]);
    }

    /**
     * Get specific user (admin only).
     */
    public function show(User $user): JsonResponse
    {
        $user->load('books');
        $user->loadCount('books');

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User retrieved successfully'
        ]);
    }

    /**
     * Delete user (admin only).
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            // Delete user's books and their thumbnails
            foreach ($user->books as $book) {
                if ($book->thumbnail) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($book->thumbnail);
                }
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}