@extends('templates.default')

@section('content')
    <div class="row">
        <div class="col-lg-5">
            @include('userblock.userblock')
            <hr>
        </div>
        <div class="col-lg-4 col-lg-offset-3">
            <!-- Friends, friend requests -->
            <!-- Add your content here -->
            @if ( Auth::user()->friendRequestSentTo($user) )
				<p>Waiting for {{ $user->getNameOrUsername() }} to accept your request</p>
			@elseif ( Auth::user()->friendRequestReceivedFrom($user) )
				<a href="{{ route('friends.acceptFriend', ['username' => $user->username]) }}" class="btn btn-primary">Accept friend request</a>
			@elseif ( Auth::user()->isFriendsWith($user) )
				<p>You and {{ $user->getNameOrUsername() }} are friends</p>
			@elseif ( Auth::user()->id !== $user->id)
				<a href="{{ route('friends.addFriend', ['username' => $user->username]) }}" class="btn btn-primary">Add as friend</a>
			@endif

            <h4>{{ $user->getFirstnameOrUsername() }}'s Friends</h4>

            @if ( ! $user->friends()->count())
				<p>{{ $user->getFirstnameOrUsername() }} currently has no friends</p>
			@else
				@foreach ($user->friends() as $user)
					@include('userblock.userblock')
				@endforeach
			@endif

        </div>
    </div>

@stop
