@extends('backend.layouts.app')
@section('title',__('Staffs Management'))
@section('content')
    <div class="clearfix  py-4">
        <div class="h4 fw-semibold float-start">{{  __('Staffs Management') }}</div>
        <a href="#new_staff_modal" data-bs-toggle="modal" class="btn btn-info float-end create-plan" >
            <x-icon name="add"  height="24" />{{ __('Add New') }}
        </a>
    </div>

    <div class="card border-0 mb-4">
        <div class="card-body">
            <div class="table-responsive  rounded">
                <table class="table mb-0 caption-top">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">{{ __('Name|Email') }}</th>
                        <th scope="col" >{{ __('Role') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Created At') }}</th>
                        <th scope="col">{{ __('Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($staffs as $staff)

                        <tr class="align-middle">
                            <td class="text-nowrap">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-1 me-2" src="{{ asset($staff->avatar) }}" alt="{{ $staff->full_name }}" height="35">
                                    <div>
                                        <div class="fw-bold">{{ $staff->full_name }}</div>
                                        <div class="text-muted small">{{ $staff->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><a class="text-nowrap badge bg-info text-white" href="{{ route('admin.role.edit', $staff->roles->first()->id) }}" target="_blank">{{ title($staff->roles->first()->name) }}</a></td>
                            <td>
                                <span class="badge bg-{{ $staff->status ? 'success' : 'danger' }}">{{ __($staff->status ? __('ACTIVE') : __('INACTIVE')) }}</span>
                            </td>
                            <td>
                                <div class="text-nowrap"> {{ $staff->created_at->format('d M, Y h:i A') }}</div>
                                <div class="small text-body-secondary text-nowrap">{{ $staff->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                @if(!$staff->roles->contains('name', 'super-admin'))
                                    <button type="button" data-edit-url="{{ route('admin.staff.edit', $staff->id) }}" class="btn btn-info d-flex align-items-center edit-modal">
                                        <x-icon name="manage" height="20"/>{{ __('Manage') }}
                                    </button>
                                @else
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('You cannot manage a super admin user from here. Please go to the role management page to update the user role.') }}">
                                        <button type="button" class="btn btn-danger d-flex align-items-center disabled" >
                                            <x-icon name="manage" height="20"/>{{ __('Not Manageable') }}
                                        </button>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    {{	$staffs->links() }}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create New Staff Modal --}}
    @include('backend.staff.partials._new_staff')

    {{-- Manage Staff Modal --}}
    @include('backend.staff.partials._edit_staff')

@endsection
@push('script')
    <script>
        $(document).ready(function () {
            editFormByModal('edit_staff_modal','edit_staff_data');
        });
    </script>
@endpush
