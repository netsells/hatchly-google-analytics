<div class="form-section__field form-section__field-inline">
    <div class="form-section__field__label">
        {!! Form::label($extension->extensionableKey(), 'OAuth 2 Authenticate') !!}
        <p>Click 'Authorise Hatchly' to get authorisation code and token.</p>
    </div>
    <div class="form-section__field__input">
        @if(empty($setting->value))
            <a class="btn btn-primary" href="{{ $extension->analyticsService->getAuthUrl() }}">Authorise Hatchly</a>
        @else
            <a class="btn btn-danger" href="/{{ config('hatchly.core.admin-url') }}/settings/analytics/deauth">Remove Authorisation</a>
        @endif
        {!! Form::hidden($extension->extensionableKey(), is_null($setting->value) ? $extension->defaultValue() : $setting->value) !!}
    </div>
</div>