@extends('backend.settings.index')
@section('setting_title', __('Site Settings'))
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
@section('setting_content')
    @php($activeSection = session('section') ?? 'general_settings')
    <div class="row g-2">
        {{-- Side Navigation --}}
        <div class="col-12 col-lg-3 col-md-6 pe-lg-2 mb-3 mb-lg-0 d-flex flex-column">
            <div class="card side-nav-card h-100">
                <div class="nav-card-header">
                    {{ __('Settings Menu') }}
                </div>
                <div class="card-body p-0">
                    <ul class="nav flex-column side-nav-list mb-2">
                        @foreach($settingMenus as $name => $icon)
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ $activeSection === $name ? 'active' : '' }} "
                                   id="v-pills-{{ $name }}-tab"
                                   data-bs-toggle="pill" href="#v-pills-{{ $name }}" role="tab"
                                   aria-controls="v-pills-{{ $name }}"
                                   aria-selected="{{ $activeSection === $name ? 'true' : 'false' }}">
                                    <x-icon name="{{ $icon }}" height="20" width="20" class="me-2 "/>
                                    <span class="me-4">{{ title($name) }}</span>
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
        <div class="col-12 col-lg-9 col-md-12 ps-lg-2">
            <div class="tab-content" id="v-pills-tabContent">
                @foreach($settings as $section => $fields)
                    <div class="tab-pane fade {{ $activeSection === $section ? 'show active' : '' }}"
                         id="v-pills-{{ $section }}" role="tabpanel" aria-labelledby="v-pills-{{ $section }}-tab">
                        <div class="card">
                            <div class="nav-card-header bg-light-+
                            ">{{ __($fields['title']) }}</div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.settings.site.update', $section) }}" enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf
                                    <div class="row gy-3">
                                        @foreach($fields['elements'] as $key => $field)
                                            <div class="{{ $field['class'] }} mb-3">
                                                @include('backend.settings.site.partials.fields.' . $field['type'], ['field' => $field])
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-info mt-3">
                                            <x-icon name="check" height="20"/> {{ __('Update Settings') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function () {
            'use strict';
            // Cache jQuery selectors
            const $siteCurrencyType = $('#site_currency_type');
            const $siteCurrency = $('#site_currency');
            const $currencySymbol = $('#currency_symbol');

            // Initial currencies data
            let currencies = @json(getJsonData('currencies'));

            // Handle currency type change
            $siteCurrencyType.on('change', function () {

                let currencyType = $(this).val();
                let selectedCurrencies = currencies[currencyType];

                // Clear and populate currency options
                $siteCurrency.empty();
                $.each(selectedCurrencies, function (key, currency) {
                    $siteCurrency.append(
                        $('<option></option>').val(currency['code']).text(`${currency['code']} : ${currency['name']}`)
                    );
                });
            });

            // Handle currency change
            $siteCurrency.on('change', function () {
                let selectedCurrency = $(this).val();

                // Find the selected currency symbol
                let currencySymbol = currencies['fiat'].find(currency => currency['code'] === selectedCurrency) ||
                    currencies['crypto'].find(currency => currency['code'] === selectedCurrency);

                // Update the currency symbol input
                if (currencySymbol) {
                    $currencySymbol.val(currencySymbol['symbol']);
                }
            });
        });
    </script>
@endpush



