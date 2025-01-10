@if(session('notifyevs'))
	<script>
        (function () {
            'use strict';
            const notifyData = JSON.parse(`{{ json_encode(session('notifyevs')) }}`.replace(/&quot;/g, '"'));
            notifyEvs(notifyData.type, notifyData.message);
        })();
	</script>
@endif

