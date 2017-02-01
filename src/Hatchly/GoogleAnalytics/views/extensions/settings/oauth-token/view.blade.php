<div class="form-section__field form-section__field-inline">
    <div class="form-section__field__label">
        {!! Form::label($extension->extensionableKey(), 'OAuth2 Token') !!}
    </div>
    <div class="form-section__field__input">
        {!! Form::text($extension->extensionableKey(), empty($setting->value) ? $extension->defaultValue() : json_decode($setting->value)->access_token, ['disabled' => true]) !!}
    </div>
</div>