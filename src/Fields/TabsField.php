<?php

namespace Gohrco\LaravelForm\Fields;

use Gohrco\LaravelForm\Fieldhandler;

class TabsField extends BaseField
{
    public function init(array $attributes, array $config)
    {
        if (empty($attributes['tabs'])) {
            throw new \Exception('No tabs declared for form');
        }

        $fields = collect();

        $tabsopenArray = array_merge($attributes, ['type' => 'tabsopen']);
        $tabscloseArray = array_merge($attributes, ['type' => 'tabsclose']);
        $tabcontentopenArray = array_merge($attributes, ['type' => 'tabcontentopen']);
        $tabcontentcloseArray = array_merge($attributes, ['type' => 'tabcontentclose']);

        $tabOpen = Fieldhandler::load($tabsopenArray, $config);
        $fields->push($tabOpen);

        foreach ($tabOpen->get('tabs') as $tab) {
            // Push the opening content div for the content of fields for this tab
            $fields->push(
                Fieldhandler::load(array_merge($tab->attributes, ['type' => 'tabcontentopen']), $config)
            );

            // Loop through the fields for the tab and push them on
            foreach ($tab->fields as $tabFieldName => $tabField) {
                $newField = Fieldhandler::load(
                    array_merge(
                        $tabField,
                        [
                            'name' => $tabFieldName,
                            'value' => $attributes['value'],
                        ]
                    ),
                    array_merge(
                        $config,
                        $tab->config
                    )
                );
                if (is_a($newField, 'Illuminate\Support\Collection')) {
                    $fields = $fields->merge($newField);
                } else {
                    $fields->push($newField);
                }
            }

            // Push the closing content div for the content of the fields for this tab
            $fields->push(
                Fieldhandler::load(array_merge($tab->attributes, ['type' => 'tabcontentclose']), $config)
            );
        }

        $fields->push(
            Fieldhandler::load($tabscloseArray, $config)
        );

        return $fields;
    }
}
