@extends('backend.layouts.app')
@section('title',__('Roles'))
@section('content')
    <div class="clearfix my-3">
        <div class="float-start fs-4 fw-semibold">{{ __('Roles & Permissions') }}</div>
        <a href="{{ route('admin.role.create') }}" class="btn btn-info float-end">
            <x-icon name="add"  height="24" />{{ __('Add New') }}
        </a>
    </div>

    <div class="card border-0 mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>{{ __('Assigned Staffs') }}</th>
                        <th>{{ __('Role Name') }}</th>
                        <th>{{ __('Role Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $role)
                        <tr class="align-middle">
                            <td>
                                <div class="avatar-group">
                                    @foreach($role->users->take(3) as $user)
                                    <a href="#" class="avatar avatar-sm" data-bs-toggle="tooltip"
                                       data-bs-placement="top" title="{{ $user->email }}">
                                        <img src="{{ asset($user->avatar) }}" alt="{{ $user->email }}"
                                             class="rounded-circle" >
                                    </a>
                                    @endforeach
                                    @if($role->users->count() > 3)
                                        <span class="avatar avatar-sm-text" >
                                            +{{ $role->users->count() - 3 }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ title($role->name) }}</div>
                                <div class="small text-body-secondary">{{ $role->description }}</div>
                            </td>
                            <td>
                                <div class="d-inline-flex gap-2">
                                    @if($role->name != 'super-admin')
                                        @can('role-edit')
                                            <a href="{{ route('admin.role.edit', $role->id) }}" class="btn btn-sm btn-info">
                                                <x-icon name="manage" height="24" />{{ __('Manage') }}
                                            </a>
                                        @endcan
                                        @can('role-delete')
                                            @if(auth()->user()->roles->first()->name != $role->name)
                                                <a href="javascript:void(0)" class="btn btn-sm btn-danger delete" data-url="{{ route('admin.role.destroy', $role->id) }}">
                                                    <x-icon name="trash" height="24" />{{ __('Delete') }}
                                                </a>
                                            @endif
                                        @endcan
                                    @else
                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger disabled">
                                            <x-icon name="warning" height="24" />{{ __('Not Allowed Any Action') }}
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="mt-3">{{ $roles->links() }}</div>
            </div>
        </div>
    </div>

@endsection
