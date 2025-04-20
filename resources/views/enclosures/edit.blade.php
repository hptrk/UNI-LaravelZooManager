<x-app-layout>
  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900 dark:text-gray-100">
                  <h1 class="text-2xl font-semibold mb-6">Edit Enclosure: {{ $enclosure->name }}</h1>

                  <div class="mb-4">
                      <a href="{{ route('enclosures.show', $enclosure) }}" class="text-blue-600 dark:text-blue-400 hover:underline">&larr; Back to Enclosure Details</a>
                  </div>

                  @if(session('error'))
                      <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                          {{ session('error') }}
                      </div>
                  @endif

                  <form action="{{ route('enclosures.update', $enclosure) }}" method="POST" class="mt-6">
                      @csrf
                      @method('PUT')

                      <!-- Name Field -->
                      <div class="mb-4">
                          <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Enclosure Name</label>
                          <input type="text" name="name" id="name" value="{{ old('name', $enclosure->name) }}" 
                                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                          @error('name')
                              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                          @enderror
                      </div>

                      <!-- Limit Field -->
                      <div class="mb-4">
                          <label for="limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Animal Limit</label>
                          <input type="number" name="limit" id="limit" value="{{ old('limit', $enclosure->limit) }}" 
                                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                          @error('limit')
                              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                          @enderror
                      </div>

                      <!-- Feeding Time Field -->
                      <div class="mb-6">
                          <label for="feeding_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Feeding Time</label>
                          <input type="time" name="feeding_at" id="feeding_at" value="{{ old('feeding_at', $enclosure->feeding_at) }}" 
                                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                          @error('feeding_at')
                              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                          @enderror
                      </div>

                      <!-- User Selection -->
                      <div class="mb-6">
                          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assign Users</label>
                          
                          <div class="space-y-2 max-h-60 overflow-y-auto p-2 border border-gray-300 dark:border-gray-600 rounded-md">
                              @forelse($users as $user)
                                  <div class="flex items-center">
                                      <input type="checkbox" name="user_ids[]" id="user_{{ $user->id }}" value="{{ $user->id }}" 
                                             class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                             {{ in_array($user->id, $selectedUserIds) ? 'checked' : '' }}>
                                      <label for="user_{{ $user->id }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                          {{ $user->name }} ({{ $user->email }})
                                      </label>
                                  </div>
                              @empty
                                  <p class="text-gray-500 dark:text-gray-400">No keepers available.</p>
                              @endforelse
                          </div>
                          @error('user_ids')
                              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                          @enderror
                      </div>

                      <!-- Current Animals Info -->
                      <div class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
                          <h3 class="font-semibold mb-2">Current Status</h3>
                          <p>Animals in enclosure: {{ $enclosure->animals->count() }} / {{ $enclosure->limit }}</p>
                      </div>

                      <!-- Submit Button -->
                      <div>
                          <button type="submit" 
                                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                              Update Enclosure
                          </button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
</x-app-layout>