<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">My Books</h2>
                    <button wire:click="openModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Add New Book
                    </button>
                </div>

                <div class="mb-4">
                    <input wire:model.live="search" type="text" placeholder="Search books..." 
                           class="w-full px-4 py-2 border rounded-lg">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($books as $book)
                        <div class="bg-gray-50 rounded-lg p-4 shadow">
                            @if($book->thumbnail)
                                <img src="{{ Storage::url($book->thumbnail) }}" alt="{{ $book->title }}" 
                                     class="w-full h-48 object-cover rounded mb-4">
                            @else
                                <div class="w-full h-48 bg-gray-200 rounded mb-4 flex items-center justify-center">
                                    <span class="text-gray-400">No Image</span>
                                </div>
                            @endif
                            
                            <h3 class="font-bold text-lg mb-2">{{ $book->title }}</h3>
                            <p class="text-gray-600 mb-2">By: {{ $book->author }}</p>
                            <p class="text-sm text-gray-500 mb-2">{{ Str::limit($book->description, 100) }}</p>
                            
                            <div class="flex items-center mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $book->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            
                            <div class="flex justify-between">
                                <button wire:click="openModal({{ $book->id }})" 
                                        class="bg-green-500 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                    Edit
                                </button>
                                <button wire:click="delete({{ $book->id }})" 
                                        wire:confirm="Are you sure you want to delete this book?"
                                        class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                    Delete
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($show_modal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="relative bg-white rounded-lg max-w-lg w-full">
                        <div class="px-6 py-4">
                            <h3 class="text-lg font-medium mb-4">
                                {{ $editing ? 'Edit Book' : 'Add New Book' }}
                            </h3>
                            
                            <form wire:submit="save">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Title</label>
                                    <input wire:model="title" type="text" 
                                           class="w-full px-3 py-2 border rounded-lg @error('title') border-red-500 @enderror">
                                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Author</label>
                                    <input wire:model="author" type="text" 
                                           class="w-full px-3 py-2 border rounded-lg @error('author') border-red-500 @enderror">
                                    @error('author') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Description</label>
                                    <textarea wire:model="description" rows="3" 
                                              class="w-full px-3 py-2 border rounded-lg @error('description') border-red-500 @enderror"></textarea>
                                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Rating</label>
                                    <select wire:model="rating" 
                                            class="w-full px-3 py-2 border rounded-lg @error('rating') border-red-500 @enderror">
                                        <option value="1">1 Star</option>
                                        <option value="2">2 Stars</option>
                                        <option value="3">3 Stars</option>
                                        <option value="4">4 Stars</option>
                                        <option value="5">5 Stars</option>
                                    </select>
                                    @error('rating') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Thumbnail</label>
                                    <input wire:model="thumbnail" type="file" accept="image/*"
                                           class="w-full px-3 py-2 border rounded-lg @error('thumbnail') border-red-500 @enderror">
                                    @error('thumbnail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex justify-end space-x-2">
                                    <button type="button" wire:click="closeModal" 
                                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                        {{ $editing ? 'Update' : 'Save' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>