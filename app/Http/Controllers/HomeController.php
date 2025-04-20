<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Enclosure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    // Main page
    public function index(){
        $enclosureCount = Enclosure::count();
        $animalCount = Animal::count();
        $user = Auth::user();
        $now = Carbon::now();
        $currentTime = $now->format('H:i');

        $enclosures = $user->enclosures()
            ->whereRaw("TIME(feeding_at) > ?", [$currentTime])
            ->orderBy('feeding_at')
            ->get();

        return view('home', compact('enclosureCount', 'animalCount', 'enclosures'));
    }
}
