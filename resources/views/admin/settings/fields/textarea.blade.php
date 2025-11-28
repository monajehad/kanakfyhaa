<div class="mb-4">
    <label class="form-label" for="setting_{{ $setting->key }}">{{ __("settings.{$setting->key}.label", ['default' => $setting->label]) }}</label>
    <textarea class="form-control" 
              id="setting_{{ $setting->key }}"
              name="settings[{{ $setting->key }}]" 
              rows="5"
              placeholder="{{ __("settings.{$setting->key}.label", ['default' => $setting->label]) }}">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
    @if($setting->description)
        <small class="form-text text-muted">{{ __("settings.{$setting->key}.description", ['default' => $setting->description]) }}</small>
    @endif
</div>
