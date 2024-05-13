<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
<!-- laravelform::components.formopen START -->

<form
    name="{{ $name }}"
    id="{{ $id }}"
    enctype="{{ $enctype }}"
    method="POST"
    action="{{ $action }}"
    >

@if ($isPut())
    <input type="hidden" name="_method" value="PUT" />
@endif

<!-- laravelform::components.formopen END -->
