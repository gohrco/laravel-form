<?php

namespace Gohrco\LaravelForm\View\Components;

use Gohrco\LaravelForm\Fields\BaseField;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Javascript extends Component
{
    public function __construct(BaseField $field)
    {
        $this->field = $field;
    }

    public function render(): string
    {
        return $this->field->renderJavascript();
    }
}
