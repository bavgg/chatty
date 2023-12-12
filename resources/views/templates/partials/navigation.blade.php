<nav class="navbar navbar-default" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{  route('home') }}">Chatty</a>
        </div>
        <div class="collapse navbar-collapse">
            @if (Auth::check())
            <ul class="nav navbar-nav">
                <li><a href="{{ route('timeline.index') }}">Timeline</a></li>
                <li><a href="{{ route('friends.index') }}">Friends</a></li>
            </ul>
            <form class="navbar-form navbar-left" role="search" method="post" action="{{ route('search.searches') }}">
                <div class="form-group">
                    <input type="text" name="query" class="form-control" placeholder="Find people">
                </div>
                <button type="submit" class="btn btn-default">Search</button>
                <input type="hidden" name="_token" value="{{ Session::token() }}">
            </form>
            @endif
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::check())
                    <li><a href="{{ route('userblock.index', ['username' => Auth::user()->username]) }}">{{ Auth::user()->getNameOrUsername() }}</a></li>
                    <li><a href="{{ route('myprofile.update') }}">Update profile</a></li>
                    <li><a href="{{ route('auth.signout') }}">Sign out</a></li>
                @else
                    <li><a href="{{ route('auth.signup') }}">Sign up</a></li>
                    <li><a href="{{ route('auth.signin') }}">Sign in</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>
