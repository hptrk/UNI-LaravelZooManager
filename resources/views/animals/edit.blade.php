<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-semibold mb-6">Edit Animal</h1>

                    <div class="mb-4">
                        <a href="{{ route('enclosures.show', $animal->enclosure_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                            &larr; Back to Enclosure
                        </a>
                    </div>

                    <form action="{{ route('animals.update', $animal) }}" method="POST" enctype="multipart/form-data" class="mt-6">
                        @csrf
                        @method('PUT')

                        <!-- Name Field -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Animal Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $animal->name) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Species Field -->
                        <div class="mb-4">
                            <label for="species" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Species</label>
                            <input type="text" name="species" id="species" value="{{ old('species', $animal->species) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                            @error('species')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Date Field -->
                        <div class="mb-4">
                            <label for="born_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Birth Date</label>
                            <input type="date" name="born_at" id="born_at" value="{{ old('born_at', $animal->born_at ? $animal->born_at->format('Y-m-d') : '') }}" max="{{ date('Y-m-d') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                            @error('born_at')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Is Predator Field -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Is this a predator?</label>
                            <div class="mt-2">
                                <div class="flex items-center">
                                    <input type="radio" name="is_predator" id="is_predator_yes" value="1" {{ old('is_predator', $animal->is_predator) ? 'checked' : '' }}
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="is_predator_yes" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                        Yes
                                    </label>
                                </div>
                                <div class="flex items-center mt-1">
                                    <input type="radio" name="is_predator" id="is_predator_no" value="0" {{ old('is_predator', $animal->is_predator) ? '' : 'checked' }}
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="is_predator_no" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                        No
                                    </label>
                                </div>
                            </div>
                            @error('is_predator')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Enclosure Field -->
                        <div class="mb-4">
                            <label for="enclosure_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Enclosure</label>
                            <select name="enclosure_id" id="enclosure_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                <option value="">-- Select an enclosure --</option>
                                @foreach($enclosures as $enclosure)
                                    <option value="{{ $enclosure->id }}" {{ (old('enclosure_id', $animal->enclosure_id) == $enclosure->id) ? 'selected' : '' }}>
                                        {{ $enclosure->name }} ({{ $enclosure->animals->count() }}/{{ $enclosure->limit }})
                                    </option>
                                @endforeach
                            </select>
                            @error('enclosure_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Image -->
                        @if($animal->image_path)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Image</label>
                                <img src="{{ asset('storage/' . $animal->image_path) }}" alt="{{ $animal->name }}" class="mt-2 h-32 w-32 object-cover rounded">
                            </div>
                        @endif

                        <!-- Image Field -->
                        <div class="mb-6">
                            <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Change Animal Image</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                   class="mt-1 block w-full">
                            <p class="text-xs text-gray-500 mt-1">Optional. Maximum file size: 2MB.</p>
                            @error('image')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update Animal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
