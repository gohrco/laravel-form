<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
<!-- laravelform::fields.content START -->
@php die('not done'); @endphp
@if ( isset($data['nowrap'] ) && $data['nowrap'] )
	{!! $data['content'] !!}
@else
	<div class="row"{{ ( isset ( $data['id'] ) ? ' id=' . $data['id']  : null ) }}>
		<div class="{{ isset( $data['class'] ) ? $data['class'] : 'col-md-6 offset-md-6' }}">
			{!! $data['content'] !!}
		</div>
	</div>
@endif

<!-- laravelform::fields.content END -->
