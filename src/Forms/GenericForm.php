<?php

namespace Gohrco\LaravelForm\Forms;

use Gohrco\LaravelForm\FieldHandler;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Route;

class GenericForm extends BaseForm
{
    protected string $bladeFile = 'laravelform::components.formopen';

    protected function buildFields(): self
    {
        $this->fields = collect();

        $commonAttributes = isset($this->contents['commonAttributes']) && $this->contents['commonAttributes'] != ''
            ? $this->contents['commonAttributes']
            : [];
        $commonConfig = isset($this->contents['config']) && $this->contents['config'] != ''
            ? $this->contents['config']
            : [];
        foreach ($this->contents['fields'] as $fieldName => $attributes) {
            $config = array_merge($commonConfig, isset($attributes['config']) ? $attributes['config'] : []);
            if (isset($attributes['config'])) {
                unset($attributes['config']);
            }
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
        foreach ($this->data as $item) {
            foreach ($this->contents['routeParameters'] as $parameter => $model) {
                if (is_a($item, $model)) {
                    $formData[$parameter] = $item;
                }
            }
        }

        $this->formData = $formData;
        return $this;
    }

    protected function setFormAction(): self
    {
        if (isset($this->contents['routeAction'])) {
            $this->formAction = $this->contents['routeAction'];
        } elseif (isset($this->contents['routeName'])) {
            $this->formAction = route($this->contents['routeName'], $this->formData);
        }

        return $this;
    }

    protected function setFormMethod(): self
    {
        if (isset($this->contents['formMethod']) && $this->contents['formMethod'] != '') {
            $this->formMethod = $this->contents['formMethod'];
            return $this;
        }

        if (isset($this->contents['routeName'])) {
            $intendedRoute = Route::getRoutes()->getByName($this->contents['routeName']);
            foreach ($intendedRoute->methods() as $method) {
                if (in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE', 'POST'])) {
                    break;
                }
            };
            $this->formMethod = $method;
        }
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

        if (!isset($this->contents['routeName']) && !isset($this->contents['routeAction'])) {
            throw new \Exception('No routeName or routeAction declared in form configuration file');
        }

        if (isset($this->contents['routeName'])) {
            $intendedRoute = Route::getRoutes()->getByName($this->contents['routeName']);
            foreach ($intendedRoute->methods() as $method) {
                if (!in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE', 'POST'])) {
                    throw new \Exception(
                        sprintf(
                            'The route named %1$s does not use PUT, PATCH, DELETE or POST methods',
                            $this->contents['routeName']
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
                if (!isset($this->contents['routeParameters'][$parameter])) {
                    throw new \Exception(
                        sprintf(
                            'Route %s requires the parameter %s to be passed in',
                            $routeName,
                            $parameter
                        )
                    );
                }
            }
        }

        return $this;
    }
}
