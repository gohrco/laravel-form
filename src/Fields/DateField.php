<?php

namespace Gohrco\LaravelForm\Fields;

use Illuminate\View\Component;

class DateField extends BaseField
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

    public function setAttributes(array $attributes): self
    {
        parent::setAttributes($attributes);

        // Format value correctly
        if ($this->get('value')) {
            $dateValue = new \DateTimeImmutable($this->get('value'));
            $this->set('value', $dateValue->format('m/d/Y'));
            unset($dateValue);
        }

        $this->javascriptArray[] = sprintf(
            'jQuery(document).ready( function() {jQuery("#%1$s").datepicker("setDate", "%2$s");});',
            $this->get('id'),
            $this->get('value')
        );

        return $this;
    }

    public function setExclusions(): self
    {
        $this->fieldExclusions[] = 'javascriptArray';
        return $this;
    }
}
