<nav>
	<ul>
		<li>{{ link_to_route('home','Home') }}</li>

		@if (Auth::check())

			Test already signed in.
		@else
			<li><a href="{{ URL::route('account-sign-in') }}">Sign in</a></li>
			<li><a href="{{ URL::route('account-create') }}">Create an account</a></li>
		@endif
	</ul>
</nav>
