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

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use craft\services\Sites;
use craft\web\Request;
use statikbe\configvaluesfield\fields\ConfigValuesFieldField as ConfigValuesFieldFieldField;
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
    public string $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Craft::$app->onInit(function () {
            Event::on(
                Fields::class,
                Fields::EVENT_REGISTER_FIELD_TYPES,
                function (RegisterComponentTypesEvent $event) {
                    $event->types[] = ConfigValuesFieldFieldField::class;
                }
            );
        });
    }

    // Protected Methods
    // =========================================================================

    // Protected Methods
    // =========================================================================
    protected function createSettingsModel(): Model
    {
        return new Settings();
    }

    public function getSiteSpecificOptions(string $dataSetKey): array
    {
        /** @var Sites $sites */
        $sites = Craft::$app->sites;
        /** @var Request $request */
        $request = Craft::$app->getRequest();

        $requestedSiteHandle = $request->getQueryParam('site');

        $primarySiteHandle = $sites->primarySite->handle;
        if ($requestedSiteHandle) {
            $site = $sites->getSiteByHandle($requestedSiteHandle);
        } else {
            $site = $sites->currentSite;
        }
        $siteHandle = $site ? $site->handle : $primarySiteHandle;

        $settings = $this->getSettings()->data;
        $settings = $settings[$dataSetKey] ?? [];

        // INFO: if no primary site or current site handle is present in the config we assume no site specific settings
        if (!isset($settings[$primarySiteHandle]) && !isset($settings[$siteHandle])) {
            return $settings;
        }

        // INFO: IF we have a site specific config we return it
        if (isset($settings[$siteHandle])) {
            return $settings[$siteHandle];
        }

        // INFO: In this case we have site specific settings but not for the current site
        return $settings[$primarySiteHandle];
    }
}
