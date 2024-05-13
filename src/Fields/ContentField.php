<?php

namespace Gohrco\LaravelForm\Fields;

use Illuminate\View\Component;

class ContentField extends BaseField
{
    protected string $type = 'hidden';
    protected string $viewFile = 'laravelform::fields.content';

    public function renderAttributes(): string
    {
        $textAttributes = 'type="hidden" ';

        return $textAttributes
            . parent::renderAttributes();
    }
}
