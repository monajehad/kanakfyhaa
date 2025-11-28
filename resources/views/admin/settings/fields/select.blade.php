<div class="mb-4">
    <label class="form-label" for="setting_{{ $setting->key }}">{{ __("settings.{$setting->key}.label", ['default' => $setting->label]) }}</label>
    <select class="form-select" 
            id="setting_{{ $setting->key }}"
            name="settings[{{ $setting->key }}]">
        @if($setting->options)
            @foreach($setting->options as $optionKey => $optionLabel)
                <option value="{{ $optionKey }}" {{ old('settings.' . $setting->key, $setting->value) === (string)$optionKey ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        @endif
    </select>
    @if($setting->description)
        <small class="form-text text-muted">{{ __("settings.{$setting->key}.description", ['default' => $setting->description]) }}</small>
    @endif
</div>
