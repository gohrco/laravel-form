<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
<!-- laravelform::fields.submit START -->

<div class="form-group row" id="{{ $field->get('id') }}-row">

	{{-- LABEL --}}
	@if ($showLabel())
        <label for="{{ $field->get('id') }}" class="{{ $field->get('labelClass') }}">{{ $field->get('label') }}</label>
	@endif

    {{-- INPUT FIELD --}}
    <div class="{{ $field->get('containerClass') }}">
		<div class="input-group row">
			<div class="dropdown col-12">
				<button {!! $field->renderAttributes() !!}>
					{{ $field->get('text', 'Submit') }}
				</button>
			</div>
		</div>
	</div>
</div>

<!-- laravelform::fields.submit END -->
