<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FriendController extends Controller
{
    public function getIndex()
	{

		$friends = Auth::user()->friends();

		$friendRequests = Auth::user()->friendRequests();

		return view('friends.index')
			->with('friends', $friends)
			->with('friendRequests', $friendRequests);
	}

    /**
	 *  SEND  a friend request
	 */
	public function getAddFriend( $username )
	{
		// get the requested user's DB object
		$user = User::where('username', $username)->first();

		// A user can't send himself a request...
		if ( Auth::user()->id === $user->id ) {
			return redirect()
				->route('home')
				->with('info', 'Are you narcissistic? :)');
		}

		// check if there was a request pending
		if (!$user) {
			return redirect()
				->route('home')
				->with('info', 'No friend request found to this user');
		}

		// check if there exists already a friend request between the two
		if ( Auth::user()->friendRequestSentTo($user) || $user->friendRequestSentTo(Auth::user()) ) {
			return redirect()
				->route('userblock.index', ['username' => $username])
				->with('info', 'Friend request already pending');
		}

		// check if they are already friends
		if ( Auth::user()->isFriendsWith($user) ) {
			return redirect()
				->route('userblock.index', ['username' => $username])
				->with('info', 'You are already friends');
		}

		// now create the friend request
		Auth::user()->addFriend( $user );

		return redirect()
			->route('userblock.index', ['username' => $username])
			->with('info', 'Friend request sent.');
	}




	/**
	 *  ACCEPT  a friend request
	 */
	public function getAcceptFriend( $username )
	{
		// get the requested user's DB object
		$user = User::where('username', $username)->first();

		// check if there was a request pending
		if (!$user) {
			return redirect()
				->route('home')
				->with('info', 'No friend request found from this user');
		}

		// check if there IS a friend request between the two
		if ( ! Auth::user()->friendRequestReceivedFrom($user) ) {
			return redirect()
				->route('userblock.index', ['username' => $username]);
		}

		// check if they are already friends
		if ( Auth::user()->isFriendsWith($user) ) {
			return redirect()
				->route('userblock.index', ['username' => $username])
				->with('info', 'You are already friends');
		}

		// now accept the friend request
		Auth::user()->acceptFriendRequest( $user );

		return back()
			->with('info', 'Friend request accepted.');
	}
}
