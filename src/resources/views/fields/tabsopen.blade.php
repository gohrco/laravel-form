<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
<!-- laravelform::fields.tabsopen START -->

<div class="form-group row" id="{{ $field->get('id') }}-row">
    <div class="col-12">
        <ul class="nav nav-tabs nav-fill">
            @foreach ($field->get('tabs') as $tab)
                @php
                    $isActive = $tab->isActive() ? ' active" aria-current="page' : '';
                @endphp
                <li class="nav-item">
                    <a
                        class="nav-link {!!$isActive!!}"
                        data-toggle="tab"
                        href="#{{$tab->id}}">
                        {{$tab->name}}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content gohrco-tabs" id="{{ $field->get('id') }}-tabcontent">
<!-- laravelform::fields.tabsopen END -->
<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
