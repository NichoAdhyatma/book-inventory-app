<div class="min-h-screen flex items-center justify-center">
    <div class="p-8 max-w-md w-full bg-white rounded shadow">
        @if ($is_success)
            <h2 class="text-xl font-semibold text-green-600 mb-4">Success!</h2>
        @else
            <h2 class="text-xl font-semibold text-red-600 mb-4">Oops!</h2>
        @endif

        <p class="text-gray-700">{{ $message }}</p>

        <div class="mt-6">
            <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Go to Login</a>
        </div>
    </div>
</div>
