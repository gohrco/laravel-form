<?php

namespace Gohrco\LaravelForm\Fields;

use Illuminate\View\Component;

class BaseField extends Component
{
    protected array $fieldAttributes = [];
    protected array $fieldConfig = [
        'labelClass' => '',
        'showDescription' => true,
        'showLabel' => true,
    ];
    protected array $fieldExclusions = [];
    protected string $type = '';
    protected string $viewFile = '';

    protected function gatherAttributes(): array
    {
        $attributes = [];
        foreach ($this->fieldAttributes as $key => $value) {
            $attributes[$key] = $value;
        }

        $attributes['field'] = $this;

        return $attributes;
    }

    public function get($item, $default = '')
    {
        return (isset($this->fieldAttributes[$item])
            ? $this->fieldAttributes[$item]
            : (isset($this->fieldConfig[$item])
                ? $this->fieldConfig[$item]
                : $default)
        );
    }

    public function getExclusions(): array
    {
        return $this->fieldExclusions;
    }

    public function init(array $attributes, array $config): self
    {
        $this->setConfig($config)
            ->setAttributes($attributes)
            ->setExclusions();

        return $this;
    }

    public function render(): \Illuminate\View\View
    {
        return view($this->viewFile, $this->gatherAttributes());
    }

    public function renderAttributes(): string
    {
        $attributeString = '';

        foreach ($this->fieldAttributes as $key => $value) {
            if (in_array($key, $this->getExclusions())) {
                continue;
            }

            $attributeString .= " {$key}=\"{$value}\"";
        }

        return $attributeString;
    }

    public function renderJavascript(string $javascript = ''): string
    {
        return $javascript;
    }

    protected function set(string $key, $value): self
    {
        if (isset($this->fieldConfig[$key])) {
            $this->fieldConfig[$key] = $value;
        } else {
            $this->fieldAttributes[$key] = $value;
        }

        return $this;
    }

    protected function setAttributes(array $attributes): self
    {
        unset($attributes['type']);

        $this->fieldAttributes = $attributes;

        if ($this->notEmpty('id') === false) {
            $this->fieldAttributes['id'] = strtolower(str_replace([' ', '_'], '-', $this->fieldAttributes['name']));
        }

        if ($this->notEmpty('label') === false) {
            $this->fieldAttributes['label'] = ucfirst($this->fieldAttributes['name']);
        }

        return $this;
    }

    protected function setConfig(array $config): self
    {
        foreach ($config as $key => $value) {
            $this->fieldConfig[$key] = $value;
        }

        return $this;
    }

    public function setExclusions(): self
    {
        $this->fieldExclusions[] = 'label';
        $this->fieldExclusions[] = 'description';
        return $this;
    }

    /**
     * Internal method to verify a fieldAttribute exists and is not empty
     *
     * @return bool
     */
    protected function notEmpty(string $key): bool
    {
        return isset($this->fieldAttributes[$key]) && !empty($this->fieldAttributes[$key]);
    }
}
