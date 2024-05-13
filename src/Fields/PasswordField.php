<?php

namespace Gohrco\LaravelForm\Fields;

use Illuminate\View\Component;

class PasswordField extends BaseField
{
    protected string $type = 'text';
    protected string $viewFile = 'laravelform::fields.password';

    public function renderAttributes(): string
    {
        return 'type="password" '
            . parent::renderAttributes();
    }
}
