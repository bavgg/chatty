<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Status;
use App\Models\Like;

class TimelineController extends Controller
{
    public function index(){
        $statuses = Status::notReply()
			->where(function($query) {
				return $query->where('user_id', Auth::user()->id)
				->orWhereIn('user_id', Auth::user()->friends()->pluck('id'));
			})
			->orderBy('created_at', 'desc')
			->paginate(5);

        return view('timeline.index')
        ->with('statuses', $statuses);

    }

    // Add a new status

	public function postUpdateStatus(Request $request) {

		$this->validate($request, [
			'status' => 'required|max:1000'
		]);

		Auth::user()->statuses()->create([
			'body' => $request->input('status'),
		]);

		return redirect()->back()
			->with('info', 'Your status has been updated.');
	}



	// Add a new reply to a status

	public function postReply(Request $request, $statusId) {

		$this->validate( $request, [
			'reply-'.$statusId => 'required|max:1000',
		], [
			'required' => 'Enter your reply here',
			'max' => 'Limit of 1000 chars applies!',
		]);

		// find the status to which this reply belongs
		$status = Status::notReply()->find($statusId);

		// fail if the status doesn't exist
		if ( ! $status ) {
			return redirect()->back()->with('info', 'Invalid status, reply cancelled');
		}

		// check if the currently authenticated user is friends with the owner of this status and not his own status
		if ( ! Auth::user()->isFriendsWith($status->user) && Auth::user()->id !== $status->user_id ) {
			return redirect()->back()->with('info', 'Please establish friendship first! Reply cancelled.');
		}

		$reply = new Status([
            'body' => $request->input("reply-{$statusId}"),
        ]);

        // Associate the user
        $reply->user()->associate(Auth::user());

        // Save the reply after associating the user
        $status->replies()->save($reply);



		return redirect()->back()
			->with('info', 'Your reply has been posted.');

		// An alternate way to create a reply is via the 'replies' method of the STATUS model:
		$reply = Status::create([
				'body' => $reply,
			])->user()->associate(Auth::user());

		// Now save it using the REPLIES method and thereby joining it via the parent_id (= status id)
		$status->replies()->save($reply);

	}



	public function getLike($statusId)
	{

		$status = Status::find($statusId);



        if (!$status) {
            // Assuming $status is a valid object, if not, handle the error accordingly.
            return redirect()->route('home');
        }

        if (!Auth::user()->isFriendsWith($status->user)) {
            return redirect()->route('home');
        }

        if (Auth::user()->hasLikedStatus($status)) {
            return redirect()->back();
        }
        $like = new Like(); // Create a new Like instance
        $like->user()->associate(Auth::user()); // Associate the user with the like
        $status->likes()->save($like); // Save the like to the status


        return redirect()->back();



	}

}
