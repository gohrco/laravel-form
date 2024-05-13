<?php

namespace Gohrco\LaravelForm\Forms;

use Gohrco\LaravelForm\FieldHandler;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Route;

class ResourceForm extends BaseForm
{
    protected string $bladeFile = 'laravelform::components.formopen';

    protected function buildFields(): self
    {
        $this->fields = collect();

        $commonAttributes = isset($this->contents['commonAttributes']) && $this->contents['commonAttributes'] != ''
            ? $this->contents['commonAttributes']
            : [];

        $model = $this->buildModel();

        foreach ($this->contents['fields'] as $fieldName => $attributes) {
            $config = $this->buildFieldConfig($attributes);

            if (isset($attributes['config'])) {
                unset($attributes['config']);
            }

            $attributes = $this->buildValue($attributes, $fieldName, $model);

            $attributes = array_merge(['name' => $fieldName], $commonAttributes, $attributes);
            $this->fields->push(
                FieldHandler::load($attributes, $config)
            );
        }

        return $this;
    }

    protected function setData(): self
    {
        $formData = [];
        foreach ($this->data['models'] as $parameter => $item) {
            foreach ($this->contents['routeParameters'] as $expectedParameter) {
                if ($parameter == $expectedParameter) {
                    $formData[$expectedParameter] = $item;
                }
            }
        }

        $this->formData = $formData;
        return $this;
    }

    protected function setFormAction(): self
    {
        $this->formAction = route($this->contents['routeName'], $this->formData);
        return $this;
    }

    protected function validateForm(): self
    {
        if (!isset($this->contents['fields'])) {
            throw new \Exception('No form fields were declared for this form');
        }

        if (!isset($this->contents['name'])) {
            throw new \Exception('No form name was declared for this form');
        }

        if (!isset($this->contents['resource'])) {
            throw new \Exception('No resource values set in form configuration file');
        }

        if (!isset($this->data['method']) || $this->data['method'] == '') {
            throw new \Exception('No method passed to loadAsResource');
        }

        if (!isset($this->contents['resource'][$this->data['method']])) {
            throw new \Exception('Invalid method passed to FormHandler, not supported by resource declaration in form');
        }

        $chosenResource = $this->contents['resource'][$this->data['method']];
        $intendedRoute = Route::getRoutes()->getByName($chosenResource['routeName']);

        foreach ($intendedRoute->methods() as $method) {
            if (!in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE', 'POST'])) {
                throw new \Exception(
                    sprintf(
                        'The route named %1$s does not use PUT, PATCH, DELETE or POST methods',
                        $chosenResource['routeName']
                    )
                );
            }
        };

        $expectedParameters = [];
        foreach (explode('/', $intendedRoute->uri) as $uriPart) {
            if (strpos($uriPart, '{') !== false) {
                $expectedParameters[] = trim($uriPart, '{}');
            }
        }

        foreach ($expectedParameters as $parameter) {
            if (!isset($this->data['models'][$parameter])) {
                throw new \Exception(
                    sprintf(
                        'Route %s requires the parameter %s to be passed in',
                        $chosenResource['routeName'],
                        $parameter
                    )
                );
            }
        }

        // Set content variables for use
        $this->contents['method'] = $chosenResource['method'];
        $this->contents['routeParameters'] = $expectedParameters;
        $this->contents['routeName'] = "{$this->contents['routeName']}.{$this->data['method']}";

        return $this;
    }
}
