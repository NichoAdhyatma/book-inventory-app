<div class="max-w-md mx-auto p-6 bg-white shadow-md rounded-lg mt-10">
    <h2 class="text-2xl font-semibold text-center mb-4">Create an Account</h2>

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="register" class="space-y-5">
        <!-- Name -->
        <div>
            <label class="block text-sm font-medium">Name</label>
            <input type="text" wire:model.defer="name"
                class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-400" />
            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Email -->
        <div>
            <label class="block text-sm font-medium">Email</label>
            <input type="email" wire:model.defer="email"
                class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-400" />
            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div>
            <label class="block text-sm font-medium">Password</label>
            <input type="password" wire:model.defer="password"
                class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-400" />
            @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label class="block text-sm font-medium">Confirm Password</label>
            <input type="password" wire:model.defer="password_confirmation"
                class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-400" />
            @error('password_confirmation') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Terms -->
        <div class="flex items-center space-x-2">
            <input type="checkbox" wire:model="terms"
                class="border-gray-300 rounded text-blue-600 shadow-sm focus:ring focus:ring-blue-200" />
            <label class="text-sm text-gray-600">I agree to the terms and conditions</label>
        </div>
        @error('terms') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

        <button type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-150">
            Create Account
        </button>
    </form>

    <p class="mt-4 text-sm text-center text-gray-500">
        Already have an account?
        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
    </p>
</div>
