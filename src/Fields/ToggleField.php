<?php

namespace Gohrco\LaravelForm\Fields;

use Illuminate\View\Component;

class ToggleField extends BaseField
{
    protected string $type = 'toggle';
    protected string $viewFile = 'laravelform::fields.toggle';
    public static $hasRun = false;

    public function hasRun(): bool
    {
        $previousValue = ToggleField::$hasRun;
        ToggleField::$hasRun = true;
        return $previousValue;
    }

    public function renderAttributes(): string
    {
        $textAttributes = 'type="checkbox" ';
        $fieldName = $this->get('name');
        $oldValue = request()->old($fieldName);

        if ($oldValue == '1') {
            $this->fieldAttributes['checked'] = 'checked';
        }

        $this->fieldAttributes['data-on-color'] = $this->get('onColor');
        $this->fieldAttributes['data-off-color'] = $this->get('offColor');
        $this->fieldAttributes['data-on-text'] = $this->get('onText');
        $this->fieldAttributes['data-off-text'] = $this->get('offText');

        return $textAttributes
            . parent::renderAttributes();
    }

    public function renderJavascript(string $javascript = ''): string
    {
        if ($this->hasRun() === false) {
            $javascript .= 'jQuery(document).ready( function() {
                jQuery(".toggle-switch").bootstrapSwitch();
            });';
        }

        return parent::renderJavascript($javascript);
    }

    protected function setAttributes(array $attributes): self
    {
        if (isset($attributes['selected']) && $attributes['selected'] == true) {
            $attributes['checked'] = 'checked';
        }

        $attributes['class'] = 'toggle-switch';
        $attributes['value'] = '1';

        parent::setAttributes($attributes);

        return $this;
    }

    protected function setConfig(array $config): self
    {
        if (!isset($config['onColor'])) {
            $config['onColor'] = 'success';
        }

        if (!isset($config['offColor'])) {
            $config['offColor'] = 'default';
        }

        if (!isset($config['onText'])) {
            $config['onText'] = 'ON';
        }

        if (!isset($config['offText'])) {
            $config['offText'] = 'OFF';
        }

        return parent::setConfig($config);
    }

    public function setExclusions(): self
    {
        $this->fieldExclusions[] = 'selected';
        return $this;
    }
}
