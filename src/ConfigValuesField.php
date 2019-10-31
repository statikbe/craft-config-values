<?php
/**
 * Config Values Field plugin for Craft CMS 3.x
 *
 * Populate a field with values from the plugin's config
 *
 * @link      https://www.statik.be
 * @copyright Copyright (c) 2019 Statik.be
 */

namespace statikbe\configvaluesfield;

use statikbe\configvaluesfield\fields\ConfigValuesFieldField as ConfigValuesFieldFieldField;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;

use statikbe\configvaluesfield\models\Settings;
use yii\base\Event;

/**
 * Class ConfigValuesField
 *
 * @author    Statik.be
 * @package   ConfigValuesField
 * @since     1.0.0
 *
 */
class ConfigValuesField extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var ConfigValuesField
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = ConfigValuesFieldFieldField::class;
            }
        );

    }

    // Protected Methods
    // =========================================================================

    // Protected Methods
    // =========================================================================
    protected function createSettingsModel()
    {
        return new Settings();
    }
}
