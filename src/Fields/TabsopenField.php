<?php

namespace Gohrco\LaravelForm\Fields;

use Illuminate\View\Component;

class TabsopenField extends ContentField
{
    protected string $type = 'tabsopen';
    protected string $viewFile = 'laravelform::fields.tabsopen';

    protected function setAttributes(array $attributes): self
    {
        $tabObjects = [];
        foreach ($attributes['tabs'] as $tabName => $tab) {
            $tabObjects[] = (new TabElement())
                ->setTabName($tabName)
                ->setTab($tab);
        }

        $attributes['tabs'] = $tabObjects;

        return parent::setAttributes($attributes);
    }
}
