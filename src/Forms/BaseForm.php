<?php

namespace Gohrco\LaravelForm\Forms;

use Gohrco\LaravelForm\FieldHandler;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Route;

abstract class BaseForm
{
    protected array $contents = [];
    protected array $data = [];
    protected string $formAction = '';
    protected array $formData = [];
    protected string $formEncType = '';
    protected string $formId = '';
    protected string $formMethod = '';
    protected string $formName = '';
    protected Collection $fields;
    protected string $bladeFile = '';

    abstract protected function buildFields(): self;

    protected function buildFieldConfig(array $attributes): array
    {
        $commonConfig = isset($this->contents['config']) && $this->contents['config'] != ''
            ? $this->contents['config']
            : [];

        $config = array_merge(
            $commonConfig,
            isset($attributes['config'])
                ? $attributes['config']
                : []
        );

        return $config;
    }

    protected function buildModel()
    {
        $model = null;
        if (isset($this->data['models'][$this->contents['modelName']])) {
            $model = $this->data['models'][$this->contents['modelName']];
        }

        return $model;
    }

    protected function buildValue(array $attributes, string $fieldName, $model = null): array
    {
        if (is_null($model)) {
            return $attributes;
        }

        $ableValue = $fieldName . '_id';
        $ableType = $fieldName . '_type';

        if (isset($model->$ableValue) && isset($model->$ableType)) {
            $attributes['able_id'] = $model->$ableValue;
            $attributes['able_type'] = $model->$ableType;
            $attributes['value'] = $model->$ableValue;
        } elseif (isset($model->$fieldName)) {
            $attributes['value'] = $model->$fieldName;
        } elseif (($attributes['type'] ?? false) && $attributes['type'] == 'tabs') {
            $attributes['value'] = $model;
        }

        return $attributes;
    }

    public function getAction(): string
    {
        return $this->formAction;
    }

    public function getEnctype(): string
    {
        return $this->formEncType;
    }

    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function getId(): string
    {
        return $this->formId;
    }

    public function getMethod(): string
    {
        return $this->formMethod;
    }

    public function getName(): string
    {
        return $this->formName;
    }

    public function init(array $contents, array $data): self
    {
        $this->contents = $contents;
        $this->data = $data;

        // Handle Form first
        $this->validateForm()
            ->setData()
            ->setFormName()
            ->setFormId()
            ->setFormEncType()
            ->setFormMethod()
            ->setFormAction();

        $this->buildFields();

        return $this;
    }

    public function render(): \Illuminate\View\View
    {
        return view($this->bladeFile, ['form' => $this]);
    }

    protected function setFormEncType(): self
    {
        $this->formEncType = (isset($this->contents['enctype']) && $this->contents['enctype'] != '')
            ? $this->contents['enctype']
            : 'multipart/form-data';
        return $this;
    }

    protected function setFormId(): self
    {
        if (isset($this->contents['id']) && $this->contents['id'] != '') {
            $this->formId = $this->contents['id'];
        } else {
            $this->formId = str_replace([' ', '.'], '-', strtolower($this->contents['name']));
        }

        return $this;
    }

    protected function setFormMethod(): self
    {
        $this->formMethod = $this->contents['method'];
        return $this;
    }

    protected function setFormName(): self
    {
        $this->formName = str_replace(' ', '_', strtolower($this->contents['name']));
        return $this;
    }

    abstract protected function validateForm(): self;
}
