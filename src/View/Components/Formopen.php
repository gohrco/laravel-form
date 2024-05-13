<?php

namespace Gohrco\LaravelForm\View\Components;

use Gohrco\LaravelForm\Forms\BaseForm;
use Illuminate\View\Component;

class Formopen extends Component
{
    public string $action = '';
    public string $enctype = '';
    public string $id = '';
    public string $method = '';
    public string $name = '';

    private BaseForm $form;

    public function __construct(BaseForm $form)
    {
        $this->action = $form->getAction();
        $this->enctype = $form->getEnctype();
        $this->id = $form->getId();
        $this->method = $form->getMethod();
        $this->name = $form->getName();

        $this->form = $form;
    }

    public function isPut(): bool
    {
        return strtoupper($this->form->getMethod()) == 'PUT';
    }

    public function render(): \Illuminate\View\View
    {
        return view('laravelform::components.formopen');
    }
}
