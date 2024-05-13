<?php

namespace Gohrco\LaravelForm\Fields;

use Illuminate\View\Component;

class SelectField extends BaseField
{
    protected string $type = 'select';
    protected string $viewFile = 'laravelform::fields.select';

    public function renderOptions(): string
    {
        $optionSettings = $this->get('options', []);
        $class = $optionSettings['class'];
        $method = $optionSettings['method'];
        $arguments = [];

        if (isset($optionSettings['arguments'])) {
            foreach ($optionSettings['arguments'] as $key => $value) {
                $arguments[$key] = $value;
            }
        }

        $options = '';
        foreach ($class::$method($arguments) as $option) {
            if (isset($option->label) && isset($option->options)) {
                $options .= "<optgroup label='{$option->label}'>";
                foreach ($option->options as $suboption) {
                    $options .= $this->renderOptionValue($suboption);
                }
                $options .= '</optgroup>';
            } else {
                $options .= $this->renderOptionValue($option);
            }
        }

        return $options;
    }

    public function setExclusions(): self
    {
        $this->fieldExclusions[] = 'options';
        return parent::setExclusions();
    }

    private function renderOptionValue(\stdClass $option): string
    {
        $selected = $this->get('value', request()->old($this->get('name')))
            ? $this->get('value', request()->old($this->get('name'))) == $option->value
            : false;
        $optionClass = $this->get('optionClass', isset($option->class) ? $option->class : false);

        return sprintf(
            '<option value="%1$s"%2$s%3$s>%4$s</option>',
            $option->value,
            $selected ? ' selected' : '',
            $optionClass ? sprintf(' class="%1$s"', $optionClass) : '',
            $option->name
        );
    }
}
