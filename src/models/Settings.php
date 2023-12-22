<?php
/**
 * Config Values Field plugin for Craft CMS 3.x
 *
 * Populate a field with values from the plugin's config
 *
 * @link      https://www.statik.be
 * @copyright Copyright (c) 2019 Statik.be
 */

namespace statikbe\configvaluesfield\models;

use craft\base\Model;

class Settings extends Model
{
    public array $data = [];

    public string $type = "";
}
