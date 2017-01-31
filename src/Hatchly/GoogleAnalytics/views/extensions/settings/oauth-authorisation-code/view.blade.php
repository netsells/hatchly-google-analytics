<div class="form-section__field form-section__field-inline">
    <div class="form-section__field__label">
        {!! Form::label($extension->extensionableKey(), 'OAuth Authorisation Code') !!}
    </div>
    <div class="form-section__field__input">
        {!! Form::text($extension->extensionableKey(), is_null($setting->value) ? $extension->defaultValue() : $setting->value) !!}
    </div>
</div>