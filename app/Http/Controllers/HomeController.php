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
        $enclosures = $user->enclosures()
            ->select('enclosures.*')
            ->where(function ($query) use ($now){
                // filter out enclosures that have already been fed today
                $query->whereRaw("CAST(CONCAT(DATE(?), ' ', TIME(feeding_at)) AS DATETIME) > ?", [
                    $now->format('Y-m-d'), 
                    $now
                ]);
            })
            ->orderBy('feeding_at')
            ->get();
        return view('home', compact('enclosureCount', 'animalCount', 'enclosures'));
    }
}
