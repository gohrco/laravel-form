<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
<!-- laravelform::fields.password START -->

<div class="form-group row" id="{{ $field->get('id') }}-row">

    @if ($showLabel())
    	<label for="{{ $field->get('id') }}" class="{{ $field->get('labelClass') }}">{{ $field->get('label') }}</label>
	@endif

    <div class="{{ $field->get('containerClass') }}">
        <div class="input-group">
            @if ($isRequired())
                <div class="input-group-prepend">
                    <span
                        class="input-group-text alert-danger text-danger"
                        id="basic-addon1"
                        data-toggle="popover"
                        data-trigger="hover"
                        title="Required"
                        data-content="This field is required to continue"
                        >
                        *
                    </span>
                </div>
			@endif

            <input {!! $field->renderAttributes() !!} />

            @if ($showDescription())
                <div class="input-group-append">
                    <span class="input-group-text" id="{{ $field->get('id') }}-inputGroupPrepend"
                        data-toggle="popover"
                        data-trigger="hover"
                        title="{{ $field->get('label') }}"
                        data-content="{{ $field->get('description') }}"
                        ><i class="fas fa-question-circle fa-lg"></i></span>
                </div>
			@endif
        </div>
    </div>

    @if ($field->get('liveupdate', false))
	<div class="col-3">
		<div class="alert alert-success text-small h6 text-muted d-none" style="position: absolute; width: 85%;">
			<i class="fas fa-check"></i> Updated !
		</div>

		<div class="alert alert-danger text-small h6 text-muted d-none" style="position: absolute; width: 85%;">
			<i class="fas fa-times"></i> Error: <br/><span style="font-size: x-small;">Error</span>
		</div>
	</div>
	@endif
</div>

<!-- laravelform::fields.password END -->
