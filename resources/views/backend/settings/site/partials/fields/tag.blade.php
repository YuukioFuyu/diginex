@include('backend.settings.site.partials.fields._level')
<input class="form-control tags-evs" name="{{ $field['key'] }}"  id="email" type="text" value="{{ setting($field['key'],$field['value']) }}">
