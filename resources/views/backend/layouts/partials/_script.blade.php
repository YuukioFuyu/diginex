<script src="{{ asset('general/js/popper.min.js') }}"></script>
<script src="{{ asset('general/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('general/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('general/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('backend/js/summernote-lite.min.js') }}"></script>
<script src="{{ asset('general/js/smooth-scroll.polyfills.min.js') }}"></script>
<script src="{{ asset('backend/js/tagify.min.js') }}"></script>
<script src="{{ asset('general/js/simple-notify.min.js') }}"></script>
<script src="{{ asset('general/js/sweetalert2.min.js') }}"></script>
<script src="{{ asset('backend/js/sortable.js') }}"></script>
<script src="{{ asset('backend/js/app.js') }}"></script>

<script src="{{ asset('general/js/apexcharts.min.js') }}" defer></script>
<script src="{{ asset('general/js/moment.js') }}"></script>
<script src="{{ asset('general/js/daterangepicker.min.js') }}"></script>
<script src="{{ asset('general/js/datepicker.min.js') }}"></script>

<script src="{{ asset('backend/js/custom.js') }}"></script>
<script src="{{ asset('general/js/helpers.js') }}"></script>
@include('general.notification-config._pusher_config')
@stack('script')
@yield('script')
@include('general._notify_evs')
@include('general._date_range_picker')
