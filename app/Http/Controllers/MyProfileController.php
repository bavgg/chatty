<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyProfileController extends Controller
{
    public function getUpdate()
	{
		return view('myprofile.update');
	}




	public function postUpdate(Request $request)
	{
		$this->validate($request, [
			'first_name' => 'alpha|max:50',
			'last_name'  => 'alpha|max:50',
			'location'   => 'max:20'
		]);

		$user = Auth::user()->update([
			'first_name' => $request->input('first_name'),
			'last_name'  => $request->input('last_name') ,
			'location'   => $request->input('location')  ,
		]);

		return redirect()
			->route('myprofile.update')
			->with('info', 'Your profile has been updated.');
	}

}
