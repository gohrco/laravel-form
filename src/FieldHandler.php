<?php

namespace Gohrco\LaravelForm;

use Gohrco\LaravelForm\Fields\BaseField;
use Illuminate\Support\Facades\App;

class FieldHandler
{
    private array $attributes = [];

    public static function load(array $attributes, array $config): BaseField
    {
        return App::make(FieldHandler::class)
            ->build($attributes, $config);
    }

    private function build(array $attributes, array $config)
    {
        $type = $this->findType($attributes);

        $field = App::make($type)
            ->init($attributes, $config);

        return $field;
    }

    /**
     * Find the type of field
     *
     * @throws Exception
     */
    private function findType(array $attributes): string
    {
        if (!isset($attributes['type']) || $attributes['type'] == '') {
            throw new \Exception(
                sprintf(
                    'Field type %1$s not %2$s',
                    isset($attributes['type']) ? $attributes['type'] : '',
                    isset($attributes['type']) && $attributes['type'] == '' ? 'set' : 'found'
                )
            );
        }

        $preliminaryType = ucfirst($attributes['type']);

        $paths = [
            config('laravelform.fieldspath.namespace') => config('laravelform.fieldspath.path'),
            '\Gohrco\LaravelForm\Fields' => __DIR__ . '/Fields',
        ];

        foreach ($paths as $namespace => $path) {
            $filePath = "$path/{$preliminaryType}Field.php";
            if (file_exists($filePath)) {
                return "$namespace\\{$preliminaryType}Field";
            }
        }

        throw new \Exception(sprintf('%1$sField not found', $preliminaryType));
    }
}
