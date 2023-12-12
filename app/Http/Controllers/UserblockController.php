<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class UserblockController extends Controller
{
    public function getIndex($username)
	{
		$user = User::where('username', $username)->first();

		if (!$user){
			abort(404);
		}

		// provide data for the timeline of this user
		// $statuses = $user->statuses()->notReply()
		// 		->orderBy('created_at', 'desc')
		// 		->paginate(10);

		return view('userblock.index')
            ->with('user', $user);
			// ->with('user', $user )
			// ->with('statuses', $statuses )
			// ->with('authUserIsFriend', Auth::user()->isFriendsWith($user) );
	}

}
