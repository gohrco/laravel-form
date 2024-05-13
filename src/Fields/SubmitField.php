<?php

namespace Gohrco\LaravelForm\Fields;

use Illuminate\View\Component;

class SubmitField extends BaseField
{
    protected string $type = 'submit';
    protected string $viewFile = 'laravelform::fields.submit';

    public function setExclusions(): self
    {
        parent::setExclusions();
        $this->fieldExclusions[] = 'offset';
        return $this;
    }
}
