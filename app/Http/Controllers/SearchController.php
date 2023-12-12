<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class SearchController extends Controller
{
    public function postSearches(Request $request){
        $query = $request->input('query');

        if (!$query) {
            return redirect()->route('home');
        }

        $users = User::where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$query}%")
            ->orWhere('username', 'LIKE', "%{$query}%")
            ->get();

        return view('search.searches')->with('users', $users);

    }
}
