<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Enclosure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'born_at' => 'required|date|before_or_equal:today',
            'is_predator' => 'required|boolean',
            'enclosure_id' => 'required|exists:enclosures,id',
            'image' => 'nullable|image|max:2048' // max 2MB
        ], [
            'image.max' => 'The uploaded file is too large. Maximum allowed size is 2MB.',
            'image.image' => 'The uploaded file must be an image.',
            'image.uploaded' => 'The uploaded file is too large. Maximum allowed size is 2MB.'
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
    public function edit(Animal $animal)
    {
        $this->authorize('update', $animal);
        
        $enclosures = Enclosure::all();
        
        return view('animals.edit', compact('animal', 'enclosures'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Animal $animal)
    {
        $this->authorize('update', $animal);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'born_at' => 'required|date|before_or_equal:today',
            'is_predator' => 'required|boolean',
            'enclosure_id' => 'required|exists:enclosures,id',
            'image' => 'nullable|image|max:2048' // max 2MB
        ], [
            'image.max' => 'The uploaded file is too large. Maximum allowed size is 2MB.',
            'image.image' => 'The uploaded file must be an image.',
            'image.uploaded' => 'The uploaded file is too large. Maximum allowed size is 2MB.'
        ]);

        // if enclosure is changed, verify capacity and predator status compatibility
        if ($animal->enclosure_id != $validated['enclosure_id']) {
            $enclosure = Enclosure::findOrFail($validated['enclosure_id']);
            
            // check if the enclosure has enough space
            if($enclosure->animals->count() >= $enclosure->limit) {
                return back()->withInput()->withErrors(['enclosure_id' => 'The selected enclosure is already at capacity.']);
            }
            
            // check if the enclosure matches the animals predator status
            $hasPredators = $enclosure->animals()->where('is_predator', true)->exists();
            $hasNonPredators = $enclosure->animals()->where('is_predator', false)->exists();

            if (($validated['is_predator'] && $hasNonPredators) || (!$validated['is_predator'] && $hasPredators)) {
                return back()
                    ->withInput()
                    ->withErrors(['enclosure_id' => 'Predators and non-predators cannot be placed in the same enclosure.']);
            }
        }

        // Handle image upload
        $imagePath = $animal->image_path;
        if ($request->hasFile('image')) {
            // delete the old image if it exists
            if ($animal->image_path) {
                Storage::disk('public')->delete($animal->image_path);
            }
            
            // store the new image
            $imagePath = $request->file('image')->store('animals', 'public');
        }

        $animal->update([
            'name' => $validated['name'],
            'species' => $validated['species'],
            'born_at' => $validated['born_at'],
            'is_predator' => $validated['is_predator'],
            'enclosure_id' => $validated['enclosure_id'],
            'image_path' => $imagePath
        ]);

        return redirect()->route('enclosures.show', $animal->enclosure_id)
            ->with('success', 'Animal updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
    /**
     * Archive an animal (with soft delete)
     */
    public function archive(Animal $animal)
    {
        $this->authorize('update', $animal);
        
        $enclosure_id = $animal->enclosure_id;
        
        $animal->enclosure_id = null;
        $animal->save();
        $animal->delete();
        
        return redirect()->route('enclosures.show', $enclosure_id)
            ->with('success', 'Animal archived successfully.');
    }

    /**
     * Archive all animals in an enclosure (with soft delete)
     */
    public function archiveAll(Enclosure $enclosure)
    {
        $this->authorize('archiveAll', Animal::class);
        
        $animals = $enclosure->animals;
        
        foreach($animals as $animal) {
            $animal->enclosure_id = null;
            $animal->save();
            $animal->delete();
        }
        
        return redirect()->route('enclosures.show', $enclosure->id)
            ->with('success', 'All animals have been archived successfully.');
    }

    /**
     * Display archived animals
     */
    public function archived()
    {
        $this->authorize('viewArchived', Animal::class);
        
        $animals = Animal::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->get();
            
        return view('animals.archived', compact('animals'));
    }
    
    /**
     * Show form for restoring an animal
     */
    public function restoreForm(int $id)
    {
        $this->authorize('restore', Animal::class);
        
        $animal = Animal::onlyTrashed()->findOrFail($id);
        $enclosures = Enclosure::all();
        
        return view('animals.restore', compact('animal', 'enclosures'));
    }
    
    /**
     * Restore an archived animal
     */
    public function restore(Request $request, int $id)
    {
        $this->authorize('restore', Animal::class);
        
        $animal = Animal::onlyTrashed()->findOrFail($id);
        
        $validated = $request->validate([
            'enclosure_id' => 'required|exists:enclosures,id',
        ]);
        
        $enclosure = Enclosure::findOrFail($validated['enclosure_id']);
        
        // check if the enclosure has enough space
        if($enclosure->animals->count() >= $enclosure->limit) {
            return back()->withInput()->withErrors(['enclosure_id' => 'The selected enclosure is already at capacity.']);
        }
        
        // check if the enclosure matches the animals predator status
        $hasPredators = $enclosure->animals()->where('is_predator', true)->exists();
        $hasNonPredators = $enclosure->animals()->where('is_predator', false)->exists();
        
        if (($animal->is_predator && $hasNonPredators) || (!$animal->is_predator && $hasPredators)) {
            return back()
                ->withInput()
                ->withErrors(['enclosure_id' => 'Predators and non-predators cannot be placed in the same enclosure.']);
        }
        
        // restore the animal and update its enclosure
        $animal->enclosure_id = $validated['enclosure_id'];
        $animal->restore();
        
        return redirect()->route('animals.archived')
            ->with('success', 'Animal restored successfully.');
    }
}
