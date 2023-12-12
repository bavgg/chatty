<div class="media">
    {{-- <a class="pull-left" href="{{ route('profile.index', ['username' => $user->username]) }}"> --}}
	<a class="pull-left" href="#">
		<img class="media-object" alt="{{ $user->getNameOrUsername() }}" src="{{ $user->getAvatarUrl() }}">
	</a>

	<div class="media-body">
		<h4 class="media-heading"><a href="{{ route('userblock.index', ['username' => $user->username]) }}">{{ $user->getNameOrUsername() }}</a></h4>
	</div>
	<p>{{ $user->location }}</p>

</div>
