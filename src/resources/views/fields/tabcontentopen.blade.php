@php
    $isActive = $field->get('active') ? ' show active' : '';
@endphp
<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
<!-- laravelform::fields.tabcontentopen START -->

<div
    class="tab-pane fade{{ $isActive }}"
    id="{{ $field->get('id') }}"
    role="tabpanel"
    aria-labelledby="{{ $field->get('id') }}-tab"
    >

<!-- laravelform::fields.tabcontentopen END -->
<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
