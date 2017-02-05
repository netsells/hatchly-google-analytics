<div class="form-section__field form-section__field-inline">
    <div class="form-section__field__label">
        {!! Form::label($extension->extensionableKey(), 'Google Analytics Profile') !!}
    </div>
    <div class="form-section__field__input">
        {!! Form::select($extension->extensionableKey(), ['' => 'Please select a profile'] + $profiles, is_null($setting->value) ? $extension->defaultValue() : $setting->value) !!}
    </div>
</div>