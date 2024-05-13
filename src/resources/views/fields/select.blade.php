<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
<!-- laravelform::fields.select START -->

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

            <select {!! $field->renderAttributes() !!}>
                {!! $field->renderOptions() !!}
            </select>

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
</div>

<!-- laravelform::fields.select END -->
