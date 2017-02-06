<div class="form-section__field form-section__field-inline">
    <div class="form-section__field__label">
        {!! Form::label($extension->extensionableKey(), 'Cache Duration (Minutes)') !!}
        <p>How often the analytics data should be updated (0 to disable)</p>
    </div>
    <div class="form-section__field__input">
        {!! Form::number($extension->extensionableKey(), is_null($setting->value) ? $extension->defaultValue() : $setting->value) !!}
    </div>
</div>