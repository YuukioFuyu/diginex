@extends('backend.layouts.app')
@section('title')
	{{ __('Profile Manage') }}
@endsection
@section('content')
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
		<div>
			<h2 class="h2 mb-0">{{ __('My Settings') }}</h2>
		</div>
	</div>

	<div class="row g-2">
		{{-- Side Navigation --}}
		<div class="col-12 col-lg-3 col-md-6 pe-lg-2 mb-3 mb-lg-0 d-flex flex-column">
			<div class="card side-nav-card h-100">
				<div class="nav-card-header">
					{{ __('Settings') }}
				</div>
				<div class="card-body p-0">
					<ul class="nav flex-column side-nav-list mb-2">
						<li class="nav-item">
							<a class="nav-link d-flex align-items-center {{ $activeSection === 'profile' ? 'active' : '' }}"
							   id="v-pills-profile-tab"
							   data-bs-toggle="pill" href="#v-pills-profile" role="tab"
							   aria-controls="v-pills-profile"
							   aria-selected="{{ $activeSection === 'profile' ? 'true' : 'false' }}">
								<i class="fa-regular fa-address-card me-2"></i>
								<span class="me-4">{{ __('Profile Edit') }}</span>
								<span class="ms-auto">
                                <i class="fa-solid fa-arrow-right-3" style="height:20px; width:20px;"></i>
                            </span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link d-flex align-items-center {{ $activeSection === 'two_fa' ? 'active' : '' }}"
							   id="v-pills-two_fa-tab"
							   data-bs-toggle="pill" href="#v-pills-two_fa" role="tab"
							   aria-controls="v-pills-two_fa"
							   aria-selected="{{ $activeSection === 'two_fa' ? 'true' : 'false' }}">
								<i class="fa-sharp fa-solid fa-shield-check me-2"></i>
								<span class="me-4">{{ __('Two-Factor Authentication') }}</span>
								<span class="ms-auto">
                                <i class="fa-solid fa-arrow-right-3" style="height:20px; width:20px;"></i>
                            </span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link d-flex align-items-center {{ $activeSection === 'change_password' ? 'active' : '' }}"
							   id="v-pills-change_password-tab"
							   data-bs-toggle="pill" href="#v-pills-change_password" role="tab"
							   aria-controls="v-pills-change_password"
							   aria-selected="{{ $activeSection === 'change_password' ? 'true' : 'false' }}">
								<i class="fa-solid fa-key-skeleton me-2"></i>
								<span class="me-4">{{ __('Change Password') }}</span>
								<span class="ms-auto">
                                <i class="fa-solid fa-arrow-right-3" style="height:20px; width:20px;"></i>
                            </span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>

		{{-- Content Area --}}
		<div class="col-12 col-lg-9 col-md-12 ps-lg-2">
			<div class="tab-content" id="v-pills-tabContent">
				<div class="tab-pane fade {{ $activeSection === 'profile' ? 'show active' : '' }}" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
					@include('backend.profile.partials._profile_edit')
				</div>
				<div class="tab-pane fade {{ $activeSection === 'two_fa' ? 'show active' : '' }}" id="v-pills-two_fa" role="tabpanel" aria-labelledby="v-pills-two_fa-tab">
					@include('backend.profile.partials._two_fa')
				</div>
				<div class="tab-pane fade {{ $activeSection === 'change_password' ? 'show active' : '' }}" id="v-pills-change_password" role="tabpanel" aria-labelledby="v-pills-change_password-tab">
					@include('backend.profile.partials._change_password')
				</div>
			</div>
		</div>
	</div>


@endsection
