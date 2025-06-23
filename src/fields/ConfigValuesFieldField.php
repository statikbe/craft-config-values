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
use craft\base\InlineEditableFieldInterface;
use craft\base\SortableFieldInterface;
use craft\helpers\App;
use statikbe\configvaluesfield\assetbundles\configvalues\ConfigValuesAsset;
use statikbe\configvaluesfield\fields\conditions\ConfigValuesFieldConditionRule;
use statikbe\configvaluesfield\ConfigValuesField;

/**
 * @author    Statik.be
 * @package   ConfigValuesField
 * @since     1.0.0
 */
class ConfigValuesFieldField extends Field implements InlineEditableFieldInterface, SortableFieldInterface
{
    // Public Properties
    // =========================================================================
    public string $dataSet = '';
    public string $type = 'dropdown';

    public const TYPE_DROPDOWN = 'dropdown';
    public const TYPE_RADIO = 'radios';
    public const TYPE_CHECKBOX = 'checkboxes';
    public const TYPE_COLOR = 'color';
    public const TYPE_SHAPE = 'shape';

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('config-values-field', 'Config values');
    }

    /**
     * @inheritdoc
     */
    public static function phpType(): string
    {
        return 'string|null';
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function defineRules(): array
    {
        $rules = parent::defineRules();
        $rules = array_merge($rules, [
            ['dataSet', 'string'],
            ['dataSet', 'required'],
            ['type', 'string'],
            ['type', 'required'],
            ['type', 'dataShouldMatchType'],
        ]);
        return $rules;
    }

    public function dataShouldMatchType($attribute, $params): void
    {
        $data = ConfigValuesField::getInstance()->getSettings()->data[$this->dataSet];
        switch ($this->type) {
            case self::TYPE_DROPDOWN:
            case self::TYPE_RADIO:
            case self::TYPE_CHECKBOX:
                foreach ($data as $key => $value) {
                    if (is_array($value)) {
                        $this->addError("$attribute", 'Each option must be a string when type is "dropdown", "radio" or "checkbox". is selected');
                        return;
                    }
                }
                break;
            case self::TYPE_COLOR:
                foreach ($data as $key => $option) {
                    if (is_array($option)) {
                        foreach ($option as $value) {
                            if (strpos($value, '#') !== 0) {
                                $this->addError("$attribute", "Each option must contain at least hex value when type is 'color'");
                                return;
                            }
                        }
                    } else {
                        if (!in_array($option, ['random', 'none'])) {
                            $this->addError("$attribute", "Each option should either contain a hex value, 'random' or 'none' when type is 'color'");
                            return;
                        }
                    }
                }
                break;

            case self::TYPE_SHAPE:
                if (!isset($data['path'])) {
                    $this->addError("$attribute", "A valid path must be configured when type is 'shape'");
                }

                if (isset($data['path'])) {
                    $path = App::parseEnv($data['path']);
                    if (!is_dir($path)) {
                        $this->addError("$attribute", "The path to the shapes must be a valid directory");
                    }
                }

                if (!isset($data['shapes'])) {
                    $this->addError("$attribute", "A set of 'shapes' must be configured when type is 'shape'");
                }

                if (isset($data['shapes']) && isset($data['path'])) {
                    $path = App::parseEnv($data['path']);
                    foreach ($data['shapes'] as $filename => $value) {
                        $filepath = $path . $filename . '.svg';
                        if (!file_exists($filepath)) {
                            $this->addError("$attribute", "The file $filename.svg does not exist in the configured path");
                        }
                    }
                }
                break;
            default:
                $this->addError($attribute, 'Invalid type.');
        }
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
        Craft::$app->getView()->registerAssetBundle(ConfigValuesAsset::class);
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
        $data = [];
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

    public function getElementConditionRuleType(): array|string|null
    {
        return ConfigValuesFieldConditionRule::class;
    }
}
