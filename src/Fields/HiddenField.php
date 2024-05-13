<?php

namespace Gohrco\LaravelForm\Fields;

use Illuminate\View\Component;

class HiddenField extends BaseField
{
    protected string $type = 'hidden';
    protected string $viewFile = 'laravelform::fields.hidden';

    public function renderAttributes(): string
    {
        $textAttributes = 'type="hidden" ';

        return $textAttributes
            . parent::renderAttributes();
    }
}
