<?php
/**
 * Config Values Field plugin for Craft CMS 3.x
 *
 * Populate a field with values from the plugin's config
 *
 * @link      https://www.statik.be
 * @copyright Copyright (c) 2019 Statik.be
 */

namespace statikbe\configvaluesfield\fields;

use Craft;

use craft\base\ElementInterface;
use craft\base\Field;
use statikbe\configvaluesfield\ConfigValuesField;
use yii\db\Schema;

/**
 * @author    Statik.be
 * @package   ConfigValuesField
 * @since     1.0.0
 */
class ConfigValuesFieldField extends Field
{
    // Public Properties
    // =========================================================================
    public string $dataSet = '';
    public string $type = 'dropdown';

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('config-values-field', 'Config values');
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
            ['dataSet', 'string'],
            ['dataSet', 'required'],
            ['type', 'string'],
            ['type', 'required'],
        ]);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public static function dbType(): string
    {
        return Schema::TYPE_STRING;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null): mixed
    {
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function serializeValue($value, ElementInterface $element = null): mixed
    {
        return parent::serializeValue($value, $element);
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(): string
    {
        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'config-values-field/_components/fields/ConfigValuesFieldField_settings',
            [
                'data' => $this->getOptions(),
                'type' => $this->type,
                'field' => $this,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {

        // Get our id and namespace
        $id = Craft::$app->getView()->formatInputId($this->handle);
        $options = ConfigValuesField::getInstance()->getSettings()->data[$this->dataSet];

        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'config-values-field/_components/fields/ConfigValuesFieldField_input.twig',
            [
                'name' => $this->handle,
                'type' => $this->type,
                'options' => $options,
                'value' => $value,
                'field' => $this,
                'id' => $id,
            ]
        );
    }


    /**
     * Get our dataset options from the 'data' array key in config/config-values-field
     * @return bool|array
     */
    private function getOptions(): array
    {
        $keys = array_keys(ConfigValuesField::getInstance()->getSettings()->data);
        if (!$keys) {
            return false;
        }
        $data[] = '---';

        foreach ($keys as $key) {
            $data[$key] = $key;
        }

        return $data;
    }
}
