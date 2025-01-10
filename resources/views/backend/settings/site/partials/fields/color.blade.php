@include('backend.settings.site.partials.fields._level')
<input class="form-control form-control-color w-100" name="{{ $field['key'] }}"  id="{{ $field['key'] }}" type="color" value="{{ setting($field['key'],$field['value']) }}" >


