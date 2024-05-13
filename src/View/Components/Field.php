<?php

namespace Gohrco\LaravelForm\View\Components;

use Gohrco\LaravelForm\Fields\BaseField;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Field extends Component
{
    public BaseField $field;

    public function __construct(BaseField $field)
    {
        $this->field = $field;
    }

    public function isRequired(): bool
    {
        return $this->field->get('required', false);
    }

    public function render(): \Illuminate\View\View
    {
        return $this->field->render();
    }

    public function showDescription(): bool
    {
        return $this->field->get('showDescription', true);
    }

    public function showLabel(): bool
    {
        return $this->field->get('showLabel', true);
    }
}
