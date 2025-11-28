<div class="mb-4">
    <label class="form-label" for="setting_{{ $setting->key }}">{{ __("settings.{$setting->key}.label", ['default' => $setting->label]) }}</label>
    <div class="input-group">
        <input type="password" 
               class="form-control" 
               id="setting_{{ $setting->key }}"
               name="settings[{{ $setting->key }}]" 
               value="{{ old('settings.' . $setting->key, $setting->value) }}"
               placeholder="{{ $setting->value ? '••••••••••••' : __("settings.{$setting->key}.label", ['default' => $setting->label]) }}">
        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('setting_{{ $setting->key }}', this)">
            <i class="bx bx-show"></i>
        </button>
    </div>
    @if($setting->description)
        <small class="form-text text-muted">{{ __("settings.{$setting->key}.description", ['default' => $setting->description]) }}</small>
    @endif
</div>

@once
@push('scripts')
<script>
function togglePasswordVisibility(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bx-show');
        icon.classList.add('bx-hide');
    } else {
        input.type = 'password';
        icon.classList.remove('bx-hide');
        icon.classList.add('bx-show');
    }
}
</script>
@endpush
@endonce
