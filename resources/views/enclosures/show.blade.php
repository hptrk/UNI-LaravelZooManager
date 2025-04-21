<x-app-layout>
  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900 dark:text-gray-100">
                  <div class="flex justify-between items-center mb-6">
                      <h1 class="text-2xl font-semibold">{{ $enclosure->name }}</h1>
                      <a href="{{ route('enclosures.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">&larr; Back to Enclosures</a>
                  </div>

                  @if(session('success'))
                      <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                          {{ session('success') }}
                      </div>
                  @endif

                  @if(session('error'))
                      <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                          {{ session('error') }}
                      </div>
                  @endif

                  <!-- Enclosure Details Section -->
                  <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow p-6 mb-6">
                      <h2 class="text-xl font-semibold mb-4">Enclosure Details</h2>
                      
                      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                          <div>
                              <p class="text-sm text-gray-600 dark:text-gray-400">Name:</p>
                              <p class="font-semibold">{{ $enclosure->name }}</p>
                          </div>
                          
                          <div>
                              <p class="text-sm text-gray-600 dark:text-gray-400">Animal Limit:</p>
                              <p class="font-semibold">{{ $enclosure->limit }}</p>
                          </div>
                          
                          <div>
                              <p class="text-sm text-gray-600 dark:text-gray-400">Current Animals:</p>
                              <p class="font-semibold">{{ $animals->count() }} / {{ $enclosure->limit }}</p>
                          </div>
                          
                          <div>
                              <p class="text-sm text-gray-600 dark:text-gray-400">Feeding Time:</p>
                              <p class="font-semibold">{{ $enclosure->feeding_at }}</p>
                          </div>
                      </div>

                      @if($hasPredators)
                          <div class="mt-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 flex items-center">
                              <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                              </svg>
                              <span>Warning: This enclosure contains predatory animals!</span>
                          </div>
                      @endif
                  </div>

                  <!-- Admin Actions Section -->
                  @if(Auth::user()->admin)
                      <div class="flex space-x-4 mb-6">
                          <a href="{{ route('enclosures.edit', $enclosure) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                              Edit Enclosure
                          </a>
                          
                          <form action="{{ route('enclosures.destroy', $enclosure) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this enclosure?');">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                  Delete Enclosure
                              </button>
                          </form>
                      </div>
                  @endif

                  <!-- Animals List Section -->
                  <div>
                      <div class="flex justify-between items-center mb-4">
                          <h2 class="text-xl font-semibold">Animals in Enclosure</h2>
                          
                          <div class="flex space-x-2">
                              @if(Auth::user()->admin && $animals->count() > 0)
                                  <form action="{{ route('animals.archive-all', $enclosure) }}" method="POST" class="inline-block">
                                      @csrf
                                      <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded flex items-center h-10" onclick="return confirm('Are you sure you want to archive all animals in this enclosure?')">
                                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                          </svg>
                                          Archive All
                                      </button>
                                  </form>
                              @endif
                              
                              @if(Auth::user()->admin && $animals->count() < $enclosure->limit)
                                  <a href="{{ route('animals.create', ['enclosure_id' => $enclosure->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center h-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span>Add Animal</span>
                                  </a>
                              @endif
                          </div>
                      </div>

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
                                              Born At
                                          </th>
                                          <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                              Predator
                                          </th>
                                          <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                              Actions
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
                                                  {{ $animal->born_at ? $animal->born_at->format('Y-m-d') : 'Unknown' }}
                                              </td>
                                              <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                                                  @if($animal->is_predator)
                                                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                          Predator
                                                      </span>
                                                  @else
                                                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                          Non-Predator
                                                      </span>
                                                  @endif
                                              </td>
                                              <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                                                @if(Auth::user()->admin)
                                                    <a href="{{ route('animals.edit', $animal) }}" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-sm mr-1">
                                                        Edit
                                                    </a>
                                            
                                                    <form action="{{ route('animals.archive', $animal) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm" onclick="return confirm('Are you sure you want to archive this animal?')">
                                                            Archive
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                          </tr>
                                      @endforeach
                                  </tbody>
                              </table>
                          </div>
                      @else
                          <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow p-6 text-center">
                              <p class="text-gray-600 dark:text-gray-400">No animals in this enclosure.</p>
                          </div>
                      @endif
                  </div>
              </div>
          </div>
      </div>
  </div>
</x-app-layout>