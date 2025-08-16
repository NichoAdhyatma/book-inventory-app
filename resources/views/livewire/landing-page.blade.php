<div class="max-w-6xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Public Books</h1>

    {{-- Filter --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <select wire:model="filter_author" class="border p-2">
            <option value="">All Authors</option>
            @foreach ($authors as $author)
                <option value="{{ $author }}">{{ $author }}</option>
            @endforeach
        </select>

        <select wire:model="filter_rating" class="border p-2">
            <option value="">All Ratings</option>
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}">{{ $i }} Stars</option>
            @endfor
        </select>

        <input type="date" wire:model="filter_date_from" class="border p-2" placeholder="From Date">
        <input type="date" wire:model="filter_date_to" class="border p-2" placeholder="To Date">
    </div>

    <div class="mb-4">
        <button wire:click="clearFilters" class="text-sm text-blue-600 hover:underline">
            Clear Filters
        </button>
    </div>

    {{-- Book List --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @forelse ($books as $book)
            <div class="border rounded p-4 shadow-sm">
                <h2 class="text-lg font-semibold">{{ $book->title }}</h2>
                <p>Author: {{ $book->author }}</p>
                <p>Rating: {{ $book->rating }} / 5</p>
                <p>Uploaded: {{ $book->created_at->format('d M Y') }}</p>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-500">
                No books found.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $books->links() }}
    </div>
</div>
