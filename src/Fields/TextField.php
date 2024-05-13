<?php

namespace Gohrco\LaravelForm\Fields;

use Illuminate\View\Component;

class TextField extends BaseField
{
    protected string $type = 'text';
    protected string $viewFile = 'laravelform::fields.text';
    private array $javascriptArray = [];

    public function renderAttributes(): string
    {
        $textAttributes = 'type="text" ';
        $fieldName = $this->get('name');
        $oldValue = request()->old($fieldName);

        if ($oldValue != '') {
            $textAttributes .= sprintf('value="%1$s" ', $oldValue);
        }

        if ($this->get('autofocus', false)) {
            $this->javascriptArray[] = sprintf(
                'jQuery(document).ready( function() {jQuery("#%1$s").focus();})',
                $this->get('id')
            );
            unset($this->fieldAttributes['autofocus']);
        }

        return $textAttributes
            . parent::renderAttributes();
    }

    public function renderJavascript(string $javascript = ''): string
    {
        $javascript .= implode("\r\n", $this->javascriptArray);
        return parent::renderJavascript($javascript);
    }

    public function setExclusions(): self
    {
        $this->fieldExclusions[] = 'javascriptArray';
        return $this;
    }
}
