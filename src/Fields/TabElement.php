<?php

namespace Gohrco\LaravelForm\Fields;

class TabElement
{
    private $tabAttributes = [];
    private $tabName = '';
    private $config = [];

    public function __get(string $name)
    {
        if ($name == 'name') {
            return $this->tabName;
        }

        if ($name == 'tabAttributes' || $name == 'attributes') {
            $attributes = $this->tabAttributes;
            unset($attributes['fields']);
            $attributes['name'] = strtolower($this->name) . 'ContentOpen';
            return $attributes;
        }

        if ($name == 'config') {
            return $this->config;
        }

        if (isset($this->tabAttributes[$name])) {
            return $this->tabAttributes[$name];
        }
    }

    public function isActive(): bool
    {
        return $this->tabAttributes['active'] ?? false;
    }

    public function setTab(array $tab): self
    {
        if ($tab['config'] ?? false) {
            $this->config = $tab['config'];
            unset($tab['config']);
        }

        $this->tabAttributes = $tab;

        if (empty($this->tabAttributes['id'])) {
            $this->tabAttributes['id'] = str_replace([' ', '_'], '-', strtolower($this->tabName));
        }

        return $this;
    }

    public function setTabName(string $name): self
    {
        $this->tabName = $name;
        return $this;
    }
}
