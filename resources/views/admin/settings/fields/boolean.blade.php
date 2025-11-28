<div class="mb-4">
    <div class="form-check form-switch">
        <input class="form-check-input" 
               type="checkbox" 
               id="setting_{{ $setting->key }}"
               name="settings[{{ $setting->key }}]" 
               value="1"
               {{ old('settings.' . $setting->key, $setting->value) == '1' ? 'checked' : '' }}>
        <label class="form-check-label" for="setting_{{ $setting->key }}">
            {{ __("settings.{$setting->key}.label", ['default' => $setting->label]) }}
        </label>
    </div>
    @if($setting->description)
        <small class="form-text text-muted d-block mt-2">{{ __("settings.{$setting->key}.description", ['default' => $setting->description]) }}</small>
    @endif
</div>
