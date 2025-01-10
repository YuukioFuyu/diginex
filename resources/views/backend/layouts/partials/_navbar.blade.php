<nav class="navbar navbar-expand navbar-dark navbar-top ps-0 pe-2 pb-2 pt-2 bg-white border-bottom rounded px-2">
	<div class="container-fluid">
		<a href="/" target="_blank" class="btn btn-sm btn-tertiary d-inline-flex align-items-center">
			<x-icon name="web" height="20" width="20"  />
			<span class="d-none d-sm-inline ms-1">{{ __('Home') }}</span>
		</a>

		<ul class="navbar-nav flex-row align-items-center">

			@if(setting('language_visibility'))
				<li class="nav-item dropdown me-3">
					<select name="locale" class="form-select me-3"  onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
						@foreach($languages as $language)
							<option value="{{ route('locale-set', $language->code) }}" @selected($language->code == app()->getLocale())>{{ $language->name }}</option>
						@endforeach
					</select>
				</li>
			@endif

			@include('backend.layouts.partials._notification')
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					<img class="avatar rounded-circle" alt="Image placeholder" src="{{ asset(auth()->user()->avatar) }}" width="40" height="40">
					<span class="d-none d-lg-inline ms-2 text-gray-900">{{ auth()->user()->full_name }}</span>
				</a>
				<ul class="dropdown-menu dropdown-menu-end mt-2 py-1">
					<li><a class="dropdown-item" href="{{ route('admin.profile.settings') }}"><x-svg i="user-setting" /> {{ __('My Settings') }}</a></li>
					<li><hr class="dropdown-divider my-1"></li>
					<li>
						<form method="POST" action="{{ route('admin.logout') }}">
							@csrf
							<button type="submit" class="dropdown-item"><x-svg i="logout" /> {{ __('Logout') }}</button>
						</form>
					</li>
				</ul>
			</li>
		</ul>
	</div>
</nav>

