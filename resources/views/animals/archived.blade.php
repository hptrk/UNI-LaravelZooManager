<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Archived Animals') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($animals->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-700 rounded-lg shadow">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            Image
                                        </th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            Species
                                        </th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            Archived At
                                        </th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($animals as $animal)
                                        <tr>
                                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                                                @if($animal->image_path)
                                                    <img src="{{ asset('storage/' . $animal->image_path) }}" alt="{{ $animal->name }}" class="h-16 w-16 object-cover rounded">
                                                @else
                                                    <img src="{{ asset('images/animal-placeholder.jpg') }}" alt="No Image" class="h-16 w-16 object-cover rounded">
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                                                {{ $animal->name }}
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                                                {{ $animal->species }}
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                                                {{ $animal->deleted_at->format('Y-m-d H:i') }}
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                                                <a href="{{ route('animals.restore.form', $animal->id) }}" class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-3 rounded text-sm">
                                                    Restore
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p>No archived animals found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
