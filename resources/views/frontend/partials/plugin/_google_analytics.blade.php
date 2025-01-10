<script async src="https://www.googletagmanager.com/gtag/js?id={{ $plugin['data']['ga_measurement_id'] }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '{{ $plugin['data']['ga_measurement_id'] }}');
</script>