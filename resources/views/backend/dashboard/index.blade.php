@extends('backend.layouts.app')
@section('title')
    {{ __('Dashboard') }}
@endsection
@section('content')
    <div class="py-2">
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">{{  __('Dashboard') }}</h1>
            </div>
        </div>
    </div>

    @can('statistics_card')
        @include('backend.dashboard.partial._statistics')
    @endcan


    <div class="row mt-3">
        {{-- Transactions Statistics Section --}}
        @can('transaction_chart')
            @include('backend.dashboard.partial._transaction_chart')
        @endcan

        {{-- Top Services Section --}}
        @can('top_services_chart')
             @include('backend.dashboard.partial._top_services')
        @endcan
    </div>

    <div class="row mt-3">
        {{-- Top Users Section --}}
        @can('latest_user')
             @include('backend.dashboard.partial._latest_user')
        @endcan

        {{-- Top Orders Section --}}
        @can('latest_order_chart')
              @include('backend.dashboard.partial._latest_order_chart')
        @endcan
    </div>

@endsection

