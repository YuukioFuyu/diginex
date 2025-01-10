@extends('backend.layouts.app')
@section('title',__('Role Create'))
@push('style')
    <style>
        @media (max-width: 1300px) {
            .col-lg-3,
            .col-lg-9 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
@endpush
@section('content')
<div class="clearfix my-3">
    <div class="fs-2 fw-semibold float-start" data-bs-i18n="dashboard">{{ __('Create Role') }}</div>
    <a href="{{ route('admin.role.index') }}"  class="btn btn-info float-end"><x-icon name="back" height="24" />{{ __('Back') }}</a>
</div>
<div class="card p-4">
    <form action="{{ route('admin.role.store') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-lg-3 col-md-4 mb-3">
                <label class="form-label">{{ __('Role Name') }}</label>
                <input type="text" name="role_name" class="form-control" placeholder="{{ __('Role Name') }}" required>
            </div>
            <div class="col-lg-9 col-md-8 mb-3">
                <label class="form-label">{{ __('Role Description') }}</label>
                <input type="text" name="description" class="form-control" placeholder="{{ __('Role Description') }}"
                       required>
            </div>
        </div>
        <h5 class="mt-3 mb-3 text-muted ">
            #{{ __('All Permissions') }}
        </h5>

        <div class="row">
            {{-- Side Navigation --}}
            <div class="side-nav-container col-lg-3 col-md-4 mb-3">
                <div class="card side-nav-card h-100">
                    <div class="nav-card-header">
                        {{ __('Role Category') }}
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav flex-column side-nav-list mb-2">
                            @foreach(array_keys($permissions->toArray()) as $category)
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center  {{ $loop->first ? 'active' : '' }} "
                                   id="v-pills-{{ $category }}-tab"
                                   data-bs-toggle="pill" data-bs-target="#v-pills-{{ $category }}" type="button"
                                   role="tab"
                                   aria-controls="v-pills-{{ $category }}"
                                   aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    <x-icon :name="$category" height="20" width="20" class="me-2 "/>
                                    <span class="me-4">{{ title($category) }}</span>
                                    <span class="ms-auto">
                                         <x-icon name="arrow-right-3" height="20" width="20"/>
                                     </span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            {{-- Content Area --}}
            <div class="col-lg-9 col-md-8 mb-3">
                <div class="tab-content" id="v-pills-tabContent">
                    @foreach($permissions as $category => $permissionList)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="v-pills-{{ $category }}"
                         role="tabpanel" aria-labelledby="v-pills-{{ $category }}-tab">
                        <div class="card">
                            <div class="nav-card-header">{{ title($category) }}</div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($permissionList as $permission)
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                        <div class="card h-100">
                                            <div class="form-check form-switch card-body p-3 rounded d-flex align-items-center h-100">
                                                <label class="form-check-label mb-0 flex-grow-1"
                                                       for="permission-{{ $permission->id }}">{{ title($permission->name) }}</label>
                                                <input class="form-check-input coevs-switch flex-shrink-0 ms-2"
                                                       type="checkbox" role="switch"
                                                       name="permission[{{ $permission->id }}]"
                                                       value="{{ $permission->id }}"
                                                       id="permission-{{ $permission->id }}"
                                                        @checked(in_array($permission->id, old('permission', [])))>
                                            </div>
                                        </div>
                                    </div>

                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-info mt-3">
                        <x-icon name="check" height="20"/>
                        {{ __('Create Role') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
