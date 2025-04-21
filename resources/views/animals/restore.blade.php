<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Restore Animal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h2 class="text-lg font-medium">Restoring: {{ $animal->name }} ({{ $animal->species }})</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            @if($animal->is_predator)
                                <span class="text-red-500">Warning: This is a predator animal</span>
                            @else
                                <span class="text-green-500">This is not a predator animal</span>
                            @endif
                        </p>
                    </div>

                    <form method="POST" action="{{ route('animals.restore', $animal->id) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Enclosure Selection -->
                        <div class="mb-4">
                            <label for="enclosure_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Select Enclosure for Restoration
                            </label>
                            <select name="enclosure_id" id="enclosure_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                <option value="">-- Select an Enclosure --</option>
                                @foreach($enclosures as $enclosure)
                                    <option value="{{ $enclosure->id }}" {{ old('enclosure_id') == $enclosure->id ? 'selected' : '' }}>
                                        {{ $enclosure->name }} ({{ $enclosure->animals->count() }}/{{ $enclosure->limit }})
                                    </option>
                                @endforeach
                            </select>
                            @error('enclosure_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <a href="{{ route('animals.archived') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Restore Animal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
