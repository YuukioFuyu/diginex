<div class="avatar-upload">
    <div class="avatar-edit">
        <input type='file' class="imageUpload" name="{{ $name }}" id="{{ $name.$ref }}_upload" data-preview-id="{{ $name.$ref }}" accept=".png, .jpg, .jpeg" />
        <label for="{{ $name.$ref }}_upload" class="imageUpload">
            <x-svg i="upload"/>
        </label>
        @if($ref === 'coevs-remove-img')
        <input type='hidden' class="imageUpload"  id="{{ $name.$ref }}-remove" data-preview-id="{{ $name.$ref }}" />
        <label for="{{ $name.$ref }}-remove" class="imageRemove">
            <x-svg i="close2"/>
        </label>
        @endif
    </div>
    <div class="avatar-preview">
        <div id="{{ $name.$ref }}" style="background-image: url('{{ asset($old ?? 'general/static/placeholder.png') }}');"></div>
    </div>
</div>

