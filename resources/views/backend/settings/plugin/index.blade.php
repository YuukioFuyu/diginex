@extends('backend.settings.index')
@section('setting_title', __('Plugins Manage'))
@section('setting_content')

    <div class="table-responsive  rounded">
        <table class="table table-striped mb-0">
            <thead class="table-light">
            <tr>
                <th scope="col">{{ __('Name | Info') }}</th>
                <th scope="col">{{ __('Status') }}</th>
                <th scope="col">{{ __('Manage') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($plugins as $plugin)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img class="avatar-img me-3" src="{{ asset($plugin->logo) }}" alt="{{ $plugin->name }}" height="30">
                            <div>
                                <div class="fw-bold">{{ $plugin->name }}</div>
                                <div class="small text-muted">{{ $plugin->description }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge p-2 {{ $plugin->status ? 'bg-success' : 'bg-danger' }}">
                            {{ $plugin->status ? __('ACTIVATED') : __('DEACTIVATED') }}
                        </span>

                    </td>
                    <td>
                        <button type="button" class="btn btn-info btn-sm d-flex align-items-center edit-modal"
                                data-edit-url="{{ route('admin.settings.plugin.edit',$plugin->id) }}">
                            <x-icon name="manage" height="18" class="me-1"/>
                            {{ __('Manage') }}
                        </button>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
    {{-- plugin mange modal --}}
    @include('backend.settings.plugin.partials._manage')

@endsection
@section('script')
    <script>
        $(document).ready(function () {
            editFormByModal('manageModal', 'edit-append', false, true);
        });
    </script>
@endsection
