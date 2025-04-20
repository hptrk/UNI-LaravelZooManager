<x-app-layout>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <h1 class="text-2xl font-semibold mb-6">List of Enclosures</h1>

          @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
              {{ session('success') }}
            </div>
          @endif

          <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-700">
              <thead>
                <tr>
                  <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                    Name
                  </th>
                  <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                    Current Number of Animals
                  </th>
                  <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                    Animal Limit
                  </th>
                  <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody>
                @forelse($enclosures as $enclosure)
                  <tr>
                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                      {{ $enclosure->name }}
                    </td>
                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                      {{ $enclosure->animals_count }}
                    </td>
                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                      {{ $enclosure->limit }}
                    </td>
                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                      <a href="{{ route('enclosures.show', $enclosure) }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm mr-1">
                        View
                      </a>

                      @if(Auth::user()->admin)
                        <a href="{{ route('enclosures.edit', $enclosure) }}" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-sm mr-1">
                          Edit
                        </a>

                        <form action="{{ route('enclosures.destroy', $enclosure) }}" method="POST" class="inline-block">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm" onclick="return confirm('Are you sure you want to delete this enclosure?')">
                            Delete
                          </button>
                        </form>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="py-4 px-4 text-center border-b border-gray-200 dark:border-gray-600">
                      No enclosures available.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="mt-4">
            {{ $enclosures->links() }}
          </div>

          @if(Auth::user()->admin)
            <div class="mt-6">
              <a href="{{ route('enclosures.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create New Enclosure
              </a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-app-layout>