<?php

namespace App\Http\Controllers;

use App\Models\Enclosure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EnclosureController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        // check if the user is admin
        if ($user->admin) {
            // get all enclosures for admins
            $enclosures = Enclosure::withCount('animals')
            ->orderBy('name')
            ->paginate(5);
        } else {
            // get only the enclosures the user is assigned to
            $enclosures = $user->enclosures()
                ->withCount('animals')
                ->orderBy('name')
                ->paginate(5);
        }

        return view('enclosures.index', compact('enclosures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // if (!Auth::user()->admin){
        //     abort(403, 'Unauthorized action.');
        // }
        
        $this->authorize('create', Enclosure::class);
        return view('enclosures.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // if (!Auth::user()->admin){
        //     abort(403, 'Unauthorized action.');
        // }

        $this->authorize('create', Enclosure::class);
        // validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'limit' => 'required|integer|min:1',
            'feeding_at' => 'required|date_format:H:i'
        ], [
            'name.required' => 'The enclosure name is required.',
            'limit.required' => 'The enclosure limit is required.',
            'limit.integer' => 'The enclosure limit must be an integer.',
            'limit.min' => 'The enclosure limit must be at least 1.',
            'feeding_at.required' => 'The feeding time is required.',
            'feeding_at.date_format' => 'The feeding time must be in the format HH:MM.'
        ]);

        // create the enclosure
        $enclosure = Enclosure::create([
            'name' => $validated['name'],
            'limit' => $validated['limit'],
            'feeding_at' => $validated['feeding_at'] . ':00', // add seconds
        ]);

        return redirect()->route('enclosures.index')->with('success', 'Enclosure created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Enclosure $enclosure)
    {
        $this->authorize('view', $enclosure);

        $animals = $enclosure->animals()
            ->orderBy('species')
            ->orderBy('born_at')
            ->get();

        // check if the enclosure has predators
        $hasPredators = $animals->contains('is_predator', true);

        return view('enclosures.show', compact('enclosure', 'animals', 'hasPredators'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enclosure $enclosure)
    {
        $this->authorize('update', $enclosure);

        $users = User::all();
        
        $selectedUserIds = $enclosure->users->pluck('id')->toArray();

        return view('enclosures.edit', compact('enclosure', 'users', 'selectedUserIds'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Enclosure $enclosure)
    {
        $this->authorize('update', $enclosure);

        // current animal count for validation
        $currentAnimalCount = $enclosure->animals()->count();

        // validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'limit' => [
                'required',
                'integer',
                'min:1',
                // custom validation to ensure limit is not less than current animal count
                function ($_, $value, $fail) use ($currentAnimalCount) {
                    if ($value < $currentAnimalCount) {
                        $fail('The enclosure limit cannot be less than the current number of animals (' . $currentAnimalCount . ').');
                    }
                },
            ],
            'feeding_at' => 'required|date_format:H:i',
            'user_ids' => 'array',
            'user_ids.*' => 'exists:users,id'
        ], [
            'name.required' => 'The enclosure name is required.',
            'limit.required' => 'The enclosure limit is required.',
            'limit.integer' => 'The enclosure limit must be an integer.',
            'limit.min' => 'The enclosure limit must be at least 1.',
            'feeding_at.required' => 'The feeding time is required.',
            'feeding_at.date_format' => 'The feeding time must be in the format HH:MM.'
        ]);

        // update enclosure data
        $enclosure->update([
            'name' => $validated['name'],
            'limit' => $validated['limit'],
            'feeding_at' => $validated['feeding_at'] . ':00', // add seconds
        ]);

        // attach users to enclosure
        // sync deletes all previous users and adds the new ones
        $enclosure->users()->sync($request->user_ids ?? []);

        return redirect()->route('enclosures.show', $enclosure)->with('success', 'Enclosure updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enclosure $enclosure)
    {
        $this->authorize('delete', $enclosure);
        
        // check if the enclosure has animals
        if ($enclosure->animals()->count() > 0){
            return redirect()->route('enclosures.show', $enclosure)
            ->with('error', 'Cannot delete enclosure. Please remove all animals first.');
        }

        // detach users from enclosure
        $enclosure->users()->detach();

        // delete the enclosure
        $enclosure->delete();

        return redirect()->route('enclosures.index')->with('success', 'Enclosure deleted successfully.');
    }
}
