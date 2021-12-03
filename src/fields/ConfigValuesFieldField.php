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

use statikbe\configvaluesfield\ConfigValuesField;
use statikbe\configvaluesfield\assetbundles\configvaluesfieldfieldfield\ConfigValuesFieldFieldFieldAsset;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use yii\db\Schema;
use craft\helpers\Json;

/**
 * @author    Statik.be
 * @package   ConfigValuesField
 * @since     1.0.0
 */
class ConfigValuesFieldField extends Field
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $dataSet = '';
    public $type = 'dropdown';

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
    public function rules()
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
    public function getContentColumnType(): string
    {
        return Schema::TYPE_STRING;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
        return parent::serializeValue($value, $element);
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
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
            'config-values-field/_components/fields/ConfigValuesFieldField_input',
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
    private function getOptions()
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
