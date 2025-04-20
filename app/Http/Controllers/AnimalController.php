<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Enclosure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AnimalController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('create', Animal::class);

        $enclosures = Enclosure::all();

        $selectedEnclosureId = $request->input('enclosure_id');
        return view('animals.create', compact('enclosures', 'selectedEnclosureId'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Animal::class);

        if ($request->hasFile('image') && $request->file('image')->getSize() > 10 * 1024 * 1024) {
            return back()
                ->withInput()
                ->withErrors(['image' => 'The uploaded file is too large. Maximum allowed size is 10MB.']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'born_at' => 'required|date|before_or_equal:today',
            'is_predator' => 'required|boolean',
            'enclosure_id' => 'required|exists:enclosures,id',
            'image' => 'nullable|image|max:10240' // max 10MB
        ]);

        // check if the enclosure has enough space
        $enclosure = Enclosure::findOrFail($validated['enclosure_id']);
        if($enclosure->animals->count() >= $enclosure->limit) {
            return back()->withInput()->withErrors(['enclosure_id' => 'The selected enclosure is already at capacity.' ]);
        }

        // check if the enclosure matches the animals predator status
        $hasPredators = $enclosure->animals()->where('is_predator', true)->exists();
        $hasNonPredators = $enclosure->animals()->where('is_predator', false)->exists();

        if (($validated['is_predator'] && $hasNonPredators) || (!$validated['is_predator'] && $hasPredators)) {
            return back()
                ->withInput()
                ->withErrors(['enclosure_id' => 'Predators and non-predators cannot be placed in the same enclosure.']);
        }

         // upload image
         $imagePath = null;
         if ($request->hasFile('image')) {
             $imagePath = $request->file('image')->store('animals', 'public');
         }

         // create the animal
         Animal::create([
            'name' => $validated['name'],
            'species' => $validated['species'],
            'born_at' => $validated['born_at'],
            'is_predator' => $validated['is_predator'],
            'enclosure_id' => $validated['enclosure_id'],
            'image_path' => $imagePath
        ]);

        return redirect()->route('enclosures.show', $validated['enclosure_id'])
        ->with('success', 'Animal created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
