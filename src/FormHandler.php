<?php

namespace Gohrco\LaravelForm;

use Illuminate\Support\Facades\App;
use Symfony\Component\Yaml\Yaml;

class FormHandler
{
    private string $filename = '';
    private array $contents = [];

    public static function load(string $formFile, ...$data): Forms\BaseForm
    {
        return App::make(FormHandler::class)
            ->find($formFile)
            ->read()
            ->build($data);
    }

    public static function loadAsResource(string $formFile, ...$data): Forms\BaseForm
    {
        $data = self::sortDataValues($data);
        return App::make(FormHandler::class)
            ->find($formFile)
            ->read()
            ->build($data);
    }

    /**
     * Build the form object based from loaded contents
     *
     * @throws Exception
     */
    private function build($data = []): Forms\BaseForm
    {
        if (isset($this->contents['class']) && !empty($this->contents['class'])) {
            $class = $this->contents['class'];
        } else {
            $class = '\Gohrco\LaravelForm\Forms\GenericForm';
        }

        $class = self::findClassname($class);

        try {
            $form = App::make($class)
                ->init($this->contents, $data);
        } catch (\Illuminate\Contracts\Container\BindingResolutionException $e) {
            throw new \Exception(sprintf('Form class %1$s could not be located for instantiation', $class));
        }

        return $form;
    }

    /**
     * Clean a formname to find associated file
     */
    private function cleanFile(string $formname): string
    {
        $nameParts = explode('.', $formname);
        if (in_array(end($nameParts), ['yml', 'yaml']) == true) {
            array_pop($nameParts);
        }

        return implode('/', $nameParts) . '.yml';
    }

    /**
     * Locate the flat file to load
     *
     * @throws Exception
     */
    private function find(string $formname): self
    {
        $paths = [
            resource_path('views/vendor/laravelform/forms'),
            __DIR__ . '/resources/forms',
        ];

        $formname = $this->cleanFile($formname);

        foreach ($paths as $path) {
            $filePath = $path . '/' . $formname;
            if (file_exists($filePath)) {
                $this->filename = $filePath;
                return $this;
            }
        }

        throw new \Exception(sprintf('Formname %1$s not found', $formname));
    }

    private function findClassname(string $class): string
    {
        $classParts = explode('\\', $class);

        // Already namespaced so return
        // Should be double checked for existance
        if (count($classParts) > 1) {
            return $class;
        }

        $paths = [
            config('laravelform.formspath.namespace') => config('laravelform.formspath.path'),
            '\Gohrco\LaravelForm\Forms' => __DIR__ . '/Forms',
        ];

        foreach ($paths as $namespace => $path) {
            $filePath = "$path/$class.php";
            if (file_exists($filePath)) {
                return "$namespace\\$class";
            }
        }

        throw new \Exception(sprintf('Form classname %1$s not found', $class));
    }

    /**
     * Read a YAML file in and return the array
     *
     * @throws Symfony\Component\Yaml\Exception\ParseException
     */
    private function read(): self
    {
        $this->contents = Yaml::parseFile($this->filename);
        return $this;
    }

    private static function sortDataValues(array $data): array
    {
        $returnData = [
            'method' => '',
            'models' => [],
            'other' => [],
        ];
        foreach ($data as $value) {
            // Value is a string and if it is either store or update it is assumed to be the resource method
            if (is_string($value) && in_array($value, ['store', 'update'])) {
                $returnData['method'] = $value;
                continue;
            }

            // An array value is assumed to be the array of models passed (ie ['model' => $model])
            if (is_array($value)) {
                $returnData['models'] = $value;
                continue;
            }

            $returnData['other'][] = $value;
        }

        return $returnData;
    }
}
