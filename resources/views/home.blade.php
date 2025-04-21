<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-6">Welcome to the LaravelZoo Manager!</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded shadow border border-gray-200 dark:border-gray-600">
                            <h2 class="text-lg font-semibold mb-2 text-blue-600 dark:text-blue-300">Statistics</h2>
                            <p class="text-gray-700 dark:text-gray-200">Number of enclosures: <strong>{{ $enclosureCount }}</strong></p>
                            <p class="text-gray-700 dark:text-gray-200">Number of animals: <strong>{{ $animalCount }}</strong></p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h2 class="text-xl font-semibold mb-4 text-blue-600 dark:text-blue-300">Today's Feeding Tasks</h2>

                        @if($enclosures->count() > 0)
                            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-600">
                                <table class="min-w-full bg-white dark:bg-gray-800">
                                    <thead>
                                        <tr>
                                            <th class="py-3 px-4 border-b border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                Enclosure Name
                                            </th>
                                            <th class="py-3 px-4 border-b border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                Feeding Time
                                            </th>
                                            <th class="py-3 px-4 border-b border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($enclosures as $enclosure)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                <td class="py-3 px-4 border-b border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300">
                                                    {{ $enclosure->name }}
                                                </td>
                                                <td class="py-3 px-4 border-b border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300">
                                                    {{ substr($enclosure->feeding_at, 0, 5) }}
                                                </td>
                                                <td class="py-3 px-4 border-b border-gray-200 dark:border-gray-600">
                                                    <a href="{{ route('enclosures.show', $enclosure) }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded text-sm">
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-600 dark:text-gray-400">No more feeding tasks for today.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>