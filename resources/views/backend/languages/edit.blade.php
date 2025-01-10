<div class="row">
	<div class="col-md-12 mb-3">
		<div>
			<label for="language_name">{{ __('Language Name') }}</label>
			<input class="form-control" name="language_name" id="language_name" value="{{ $language->name }}"  type="text" placeholder="Enter Language name" required>
		</div>
	</div>
	<div class="col-md-12 mb-3">
		<div>
			<label for="language_code">{{ __('Language Code') }}</label>
			<input class="form-control" name="language_code" id="language_code" @disabled($language->code == 'en') value="{{ $language->code }}"  type="text" placeholder="EnterLanguage Code" required>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-4 mb-3 mt-2">
		<div>
			<div class="form-check form-switch">
				<label class="form-check-label" for="default">{{ __('Default') }}</label>
				<input class="form-check-input" type="checkbox" value="1" name="is_default" @checked($language->is_default)  id="default">
			</div>
		</div>
	</div>
	<div class="col-md-4 mb-3 mt-2">
		<div>
			<div class="form-check form-switch">
				<label class="form-check-label" for="rtl">{{ __('Rtl') }}</label>
				<input class="form-check-input" type="checkbox" value="1" name="is_rtl" @checked($language->is_rtl)  id="rtl">
			</div>
		</div>
	</div>
	<div class="col-md-4 mb-3 mt-2">
		<div>
			<div class="form-check form-switch">
				<label class="form-check-label" for="status">{{ __('Status') }}</label>
				<input class="form-check-input" type="checkbox"  value="1" name="status" @checked($language->status)  id="status">
			</div>
		</div>
	</div>
</div>