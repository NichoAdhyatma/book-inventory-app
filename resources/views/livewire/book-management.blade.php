<div>
    @section('title', 'My Books')
    
    <div class="p-6">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Books</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your personal book collection</p>
            </div>
            <button wire:click="openModal" 
                    class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-primary-700 transition">
              
                Add New Book
            </button>
        </div>

        <!-- Search & Sort Bar -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Search -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" 
                               type="text" 
                               placeholder="Search books..."
                               class="pl-10 w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                <!-- Sort Options -->
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Sort by:</span>
                    <button wire:click="sortBy('title')" 
                            class="px-3 py-1 text-sm rounded {{ $sort_by === 'title' ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        Title
                        @if($sort_by === 'title')
                            <svg class="inline w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                @if($sort_direction === 'asc')
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                @else
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                @endif
                            </svg>
                        @endif
                    </button>
                    <button wire:click="sortBy('author')" 
                            class="px-3 py-1 text-sm rounded {{ $sort_by === 'author' ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        Author
                        @if($sort_by === 'author')
                            <svg class="inline w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                @if($sort_direction === 'asc')
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                @else
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                @endif
                            </svg>
                        @endif
                    </button>
                    <button wire:click="sortBy('rating')" 
                            class="px-3 py-1 text-sm rounded {{ $sort_by === 'rating' ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        Rating
                        @if($sort_by === 'rating')
                            <svg class="inline w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                @if($sort_direction === 'asc')
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                @else
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                @endif
                            </svg>
                        @endif
                    </button>
                    <button wire:click="sortBy('created_at')" 
                            class="px-3 py-1 text-sm rounded {{ $sort_by === 'created_at' ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        Date
                        @if($sort_by === 'created_at')
                            <svg class="inline w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                @if($sort_direction === 'asc')
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                @else
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                @endif
                            </svg>
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <!-- Books Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($books as $book)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <!-- Book Cover -->
                    <div class="relative h-64 bg-gray-200 dark:bg-gray-700">
                        @if($book->thumbnail)
                            <img src="{{ Storage::url($book->thumbnail) }}" 
                                 alt="{{ $book->title }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.669 0-3.218.51-4.5 1.385V4.804z"/>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Rating Badge -->
                        <div class="absolute top-2 right-2 bg-black bg-opacity-70 rounded-full px-2 py-1">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="ml-1 text-white text-sm font-medium">{{ $book->rating }}/5</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Book Details -->
                    <div class="p-4">
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-1 truncate">
                            {{ $book->title }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            by {{ $book->author }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-4">
                            {{ $book->description }}
                        </p>
                        
                        <!-- Action Buttons -->
                        <div class="flex justify-between items-center">
                            <button wire:click="openModal({{ $book->id }}, true)" 
                                    class="text-blue-600 hover:text-blue-500 dark:text-primary-400 dark:hover:text-primary-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            <button wire:click="openModal({{ $book->id }})" 
                                    class="text-green-600 hover:text-green-500 dark:text-green-400 dark:hover:text-green-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button wire:click="delete({{ $book->id }})" 
                                    wire:confirm="Are you sure you want to delete '{{ $book->title }}'?"
                                    class="text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No books found</h3>
                        <p class="mt-2 text-gray-500 dark:text-gray-400">
                            @if($search)
                                No books match your search criteria.
                            @else
                                Get started by adding your first book to your collection.
                            @endif
                        </p>
                        <button wire:click="openModal" 
                                class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-primary-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Your First Book
                        </button>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($books->hasPages())
            <div class="mt-6">
                {{ $books->links() }}
            </div>
        @endif

        <!-- Modal -->
        @if($show_modal)
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4">
                        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full">
                            <!-- Modal Header -->
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                        @if($viewing)
                                            Book Details
                                        @elseif($editing)
                                            Edit Book
                                        @else
                                            Add New Book
                                        @endif
                                    </h3>
                                    <button wire:click="closeModal" 
                                            class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Modal Body -->
                            <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
                                @if($viewing)
                                    <!-- View Mode -->
                                    <div class="space-y-4">
                                        @if($existing_thumbnail)
                                            <div class="w-full h-64 rounded-lg overflow-hidden">
                                                <img src="{{ Storage::url($existing_thumbnail) }}" 
                                                     alt="{{ $title }}" 
                                                     class="w-full h-full object-cover">
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                                            <p class="text-gray-900 dark:text-white">{{ $title }}</p>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Author</label>
                                            <p class="text-gray-900 dark:text-white">{{ $author }}</p>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                                            <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $description }}</p>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rating</label>
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-6 h-6 {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" 
                                                         fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                                <span class="ml-2 text-gray-600 dark:text-gray-400">({{ $rating }}/5)</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Edit/Create Mode -->
                                    <form wire:submit="save" class="space-y-4">
                                        <!-- Title -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Title <span class="text-red-500">*</span>
                                            </label>
                                            <input wire:model="title" 
                                                   type="text" 
                                                   class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('title') border-red-500 @enderror"
                                                   placeholder="Enter book title">
                                            @error('title')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Author -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Author <span class="text-red-500">*</span>
                                            </label>
                                            <input wire:model="author" 
                                                   type="text" 
                                                   class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('author') border-red-500 @enderror"
                                                   placeholder="Enter author name">
                                            @error('author')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Description -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Description <span class="text-red-500">*</span>
                                            </label>
                                            <textarea wire:model="description" 
                                                      rows="4" 
                                                      class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('description') border-red-500 @enderror"
                                                      placeholder="Enter book description"></textarea>
                                            @error('description')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Rating -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Rating <span class="text-red-500">*</span>
                                            </label>
                                            <div class="flex items-center space-x-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <button type="button"
                                                            wire:click="$set('rating', {{ $i }})"
                                                            class="focus:outline-none">
                                                        <svg class="w-8 h-8 {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }} hover:text-yellow-400 transition-colors" 
                                                             fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    </button>
                                                @endfor
                                                <span class="ml-2 text-gray-600 dark:text-gray-400">({{ $rating }}/5)</span>
                                            </div>
                                            @error('rating')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Thumbnail -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Book Cover
                                            </label>
                                            
                                            @if($existing_thumbnail && !$thumbnail)
                                                <div class="mb-3">
                                                    <img src="{{ Storage::url($existing_thumbnail) }}" 
                                                         alt="Current thumbnail" 
                                                         class="w-32 h-44 object-cover rounded">
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Current cover</p>
                                                </div>
                                            @endif
                                            
                                            <div class="flex items-center justify-center w-full">
                                                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500">
                                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                        <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                        </svg>
                                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                            <span class="font-semibold">Click to upload</span> or drag and drop
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG or WEBP (MAX. 2MB)</p>
                                                    </div>
                                                    <input wire:model="thumbnail" type="file" accept="image/*" class="hidden">
                                                </label>
                                            </div>
                                            
                                            @if($thumbnail)
                                                <div class="mt-3">
                                                    <p class="text-sm text-green-600 dark:text-green-400">
                                                        ✓ New image selected: {{ $thumbnail->getClientOriginalName() }}
                                                    </p>
                                                </div>
                                            @endif
                                            
                                            @error('thumbnail')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </form>
                                @endif
                            </div>

                            <!-- Modal Footer -->
                            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                                @if($viewing)
                                    <button wire:click="$set('viewing', false)" 
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-primary-700 transition">
                                        Edit Book
                                    </button>
                                    <button wire:click="closeModal" 
                                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition">
                                        Close
                                    </button>
                                @else
                                    <button wire:click="closeModal" 
                                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition">
                                        Cancel
                                    </button>
                                    <button wire:click="save" 
                                            wire:loading.attr="disabled"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-primary-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span wire:loading.remove>{{ $editing ? 'Update' : 'Save' }} Book</span>
                                        <span wire:loading class="inline-flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Saving...
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>