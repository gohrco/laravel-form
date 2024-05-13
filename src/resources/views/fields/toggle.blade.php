<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
<!-- laravelform::fields.toggle START -->

<div class="form-group row" id="{{ $field->get('id') }}-row">

    @if ($showLabel())
    	<label for="{{ $field->get('id') }}" class="{{ $field->get('labelClass') }}">{{ $field->get('label') }}</label>
	@endif

    <div class="{{ $field->get('containerClass') }}">
        <div class="input-group">

            <input type="hidden" name="{{ $field->get('name') }}" value="0" />

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
</div>

<!-- laravelform::fields.toggle END -->
