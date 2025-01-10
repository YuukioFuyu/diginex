<div class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-2">
	<div class="d-flex align-items-center">
		<a href="{{ route('admin.dashboard') }}" class="nav-link d-flex align-items-center d-md-none">
			<img src="{{ asset(setting('light_logo')) }}" height="40" alt="Volt Logo">
		</a>
	</div>
	<div class="collapse-close d-md-none">
		<a href="#sidebarMenu" data-bs-toggle="collapse"
		   data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="true"
		   aria-label="Toggle navigation">
			<x-svg i="close"/>
		</a>
	</div>
</div>