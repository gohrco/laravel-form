<?php

namespace Gohrco\LaravelForm\Fields;

use Illuminate\View\Component;

class TabscloseField extends ContentField
{
    protected string $type = 'tabsopen';
    protected string $viewFile = 'laravelform::fields.tabsclose';
}
