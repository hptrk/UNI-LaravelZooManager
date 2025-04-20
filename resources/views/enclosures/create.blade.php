<x-app-layout>
  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900 dark:text-gray-100">
                  <h1 class="text-2xl font-semibold mb-6">Create New Enclosure</h1>

                  <div class="mb-4">
                      <a href="{{ route('enclosures.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">&larr; Back to Enclosures</a>
                  </div>

                  <form action="{{ route('enclosures.store') }}" method="POST" class="mt-6">
                      @csrf

                      <!-- Name Field -->
                      <div class="mb-4">
                          <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Enclosure Name</label>
                          <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600"
                                 {{-- required | no frontend validation --}}>
                          @error('name')
                              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                          @enderror
                      </div>

                      <!-- Limit Field -->
                      <div class="mb-4">
                          <label for="limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Animal Limit</label>
                          <input type="number" name="limit" id="limit" value="{{ old('limit') }}" 
                                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600"
                                  {{-- required min="1" | no frontend validation --}}>
                          @error('limit')
                              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                          @enderror
                      </div>

                      <!-- Feeding Time Field -->
                      <div class="mb-6">
                          <label for="feeding_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Feeding Time</label>
                          <input type="time" name="feeding_at" id="feeding_at" value="{{ old('feeding_at') }}" 
                                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600"
                                 {{-- required | no frontend validation --}}>
                          @error('feeding_at')
                              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                          @enderror
                      </div>

                      <!-- Submit Button -->
                      <div>
                          <button type="submit" 
                                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                              Create Enclosure
                          </button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
</x-app-layout>